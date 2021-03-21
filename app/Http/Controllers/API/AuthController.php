<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseApiController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            $this->response->addValidatorErrorMessages($validator->errors(), 400);
            return $this->response->generateJSONResponse();
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request["password"]
        ];

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->plainTextToken;

        $this->response->addItem('user', $user);
        $this->response->addItem('access_token', $accessToken);
        $this->response->setStatusCode(201);
        return $this->response->generateJSONResponse();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response->addValidatorErrorMessages($validator->errors(), 400);
            return $this->response->generateJSONResponse();
        }

        if (!Auth::attempt($request->all())) {
            $this->response->addErrorMessageWithErrorCode('ERROR_INVALID_USER_CREDENTIALS', 400);
            return $this->response->generateJSONResponse();

        }

        $accessToken = auth()->user()->createToken('authToken')->plainTextToken;
        $this->response->addItem('user', auth()->user());
        $this->response->addItem('access_token', $accessToken);

        return $this->response->generateJSONResponse();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->response->generateJSONResponse();
    }
}
