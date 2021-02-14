<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    use ApiResponder;

    public function trainings(Request $request)
    {
        $user = $request->user();
        $trainings = Training::all()->where('user_id', $user->id);

        return $this->success(['trainings' => $trainings]);
    }

    public function create(Request $request)
    {
        $validateData = $request->validate([
            'length' => 'required|integer|min:0'
        ]);

        $validateData['user_id'] = $request->user()->id;
        $training = Training::create($validateData);
        return $this->success(['training' => $training], 'Training was saved.', 201);
    }
}
