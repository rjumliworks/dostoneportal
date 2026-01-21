<?php

namespace App\Services\HumanResource\Survey;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Http\Resources\HumanResource\Survey\IndexResource;

class SaveClass
{
    public function store($request){
        $data = Survey::create(array_merge($request->all(), ['user_id' => \Auth::user()->id, 'reports' => json_encode([])]));
        $data = Survey::with('semester')->withCount('answers')->where('id',$data->id)->first();
        return [
            'data' => new IndexResource($data),
            'message' => 'Survey creation was successful!', 
            'info' => "You've successfully created a new survey."
        ];
    }

    public function answers($request){
        foreach($request->questions as $question){
            $count = SurveyAnswer::where('survey_id',$question['survey_id'])->where('question_id',$question['id'])->where('user_id',\Auth::user()->id)->count();
            if($count == 0){
                $data = SurveyAnswer::create([
                    'rating' => $question['rating'],
                    'question_id' => $question['id'],
                    'survey_id' => $question['survey_id'],
                    'user_id' => \Auth::user()->id,
                ]);
            }
        }

        return [
            'data' => [],
            'message' => 'Morale Survey was submitted', 
            'info' => "You've successfully submitted the survey."
        ];
    }
}
