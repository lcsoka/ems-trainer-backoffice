<?php

namespace App\Http\Controllers\API;

use App\Achievements\FirstCardioTraining;
use App\Achievements\FirstMassageTraining;
use App\Achievements\FirstStrengthTraining;
use App\Models\Training;
use App\Models\TrainingValues;
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
            'training_mode' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response->addValidatorErrorMessages($validator->errors(), 400);
            return $this->response->generateJSONResponse();
        }

        $user = $request->user();
        $trainingMode = $request->input('training_mode');

        $date = date("Y-m-d H:i:s", $request->input('date'));

        $data = [
            'length' => $request->input('length'),
            'training_mode' => $trainingMode,
            'user_id' => $user->id,
            'date' => $date,
        ];

        $training = Training::create($data);

        $values = $request->input('training_values');
        $trainingValuesJson = $values;
        if($trainingValuesJson != null) {
            $trainingValuesData = [
                'training_id' => $training->id,
                'values' => json_encode($values)
            ];
            TrainingValues::create($trainingValuesData);
//            $this->response->addItem('trainingValues', $trainingValues);
        }


        if($trainingMode == 'cardio') {
            $user->unlock(new FirstCardioTraining());
        } elseif ($trainingMode == 'strength') {
            $user->unlock(new FirstStrengthTraining());
        } elseif ($trainingMode == 'massage') {
            $user->unlock(new FirstMassageTraining());
        }

        $this->response->addItem('trainings', $user->trainings()->with('trainingValues')->get()->map(function ($training) {
            $data = $training;
            $originalValues = $data['trainingValues'];
            if ($originalValues != null) {
                $values= json_decode($originalValues->values);
                $training['training_values'] = $values;
            }
            return $training;
        }));

        return $this->response->generateJSONResponse();
    }
}
