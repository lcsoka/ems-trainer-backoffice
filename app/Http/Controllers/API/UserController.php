<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponder;

    public function me(Request $request)
    {
        $user = $request->user();
        $trainings = Training::all()->where('user_id',$user->id);
        return $this->success(['user' => $user, 'trainings' => $trainings]);
    }

    public function test(Request $request)
    {
        sleep(5);
        return $this->success([]);
    }

}
