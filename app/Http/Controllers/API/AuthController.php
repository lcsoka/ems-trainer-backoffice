<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponder;

    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $validateData["password"] = Hash::make($validateData["password"]);

        $user = User::create($validateData);

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return $this->success(['user' => $user, 'access_token' => $accessToken], 'User was created.', 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->addValidatorErrorMessages($validator->errors(), 400);
            return $this->generateJSONResponse();
        }

        if (!Auth::attempt($request->all())) {
            return $this->error([["error" => 'Invalid email or password.']], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->plainTextToken;

        return $this->success(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out.');
    }
}
