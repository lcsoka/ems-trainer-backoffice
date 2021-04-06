<?php

namespace App\Http\Controllers\API;

use App\Achievements\FirstCardioTraining;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingController extends BaseApiController
{
    public function trainings(Request $request)
    {
        $user = $request->user();
        $trainings = Training::all()->where('user_id', $user->id);
        $this->response->addItem('trainings', $trainings);
        return $this->response->generateJSONResponse();
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'required|integer|min:0',
            'training_mode' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response->addValidatorErrorMessages($validator->errors(), 400);
            return $this->response->generateJSONResponse();
        }

        $user = $request->user();
        $trainingMode = $request->input('training_mode');

        $data = [
            'length' => $request->input('length'),
            'training_mode' => $trainingMode,
            'user_id' => $user->id
        ];

        $training = Training::create($data);

        if($trainingMode == 'cardio') {
            $user->unlock(new FirstCardioTraining());
        }

        $this->response->addItem('training', $training);
        return $this->response->generateJSONResponse();
    }
}
