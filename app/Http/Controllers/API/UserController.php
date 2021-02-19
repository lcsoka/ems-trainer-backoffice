<?php

namespace App\Http\Controllers\API;

use App\Models\Training;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{

    public function me(Request $request)
    {
        $user = $request->user();
        $trainings = Training::all()->where('user_id', $user->id);
        $this->response->addItem('user', $user);
        $this->response->addItem('trainings', $trainings);
        return $this->response->generateJSONResponse();
    }

    public function test(Request $request)
    {
        sleep(5);
        return $this->response->generateJSONResponse();
    }

}
