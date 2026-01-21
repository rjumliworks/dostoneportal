<?php

namespace App\Services\HumanResource\Survey;

use Hashids\Hashids;
use App\Models\User;
use App\Models\ListData;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\UserOrganization;
use App\Models\ListDropdown;
use App\Models\ListUnit;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\HumanResource\Survey\IndexResource;

class ViewClass
{
    public function lists($request){
        $data = IndexResource::collection(
            Survey::with('semester')
            ->withCount('answers')
            ->when($request->year, function ($query,$keyword) {
                $query->where('year', 'LIKE', "%{$keyword}%");
            })
            ->when($request->semester, function ($query,$semester) {
                $query->where('semester_id',$semester);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = new DefaultResource(
            Survey::with('semester','answers','user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id') ->where('id',$id)->first()
        );
        return $data;
    }

    public function counts($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);
        $statuses = ListData::where('is_active',1)->where('type','Employment Status')->get()->map(function ($item){
            $status = $item->id;

            $averageRating = User::where('is_active', 1)
                ->join('user_organizations', 'users.id', '=', 'user_organizations.user_id')
                ->where('user_organizations.type_id', $status)
                ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id')
                ->selectRaw('AVG(COALESCE(survey_answers.rating, 0)) as overall_rating')
                ->value('overall_rating');

            $ratingPercentage = round(($averageRating / 5) * 100, 2);

            return [
                'name' => $item->name,
                'count' =>  $ratingPercentage .'%',
                'description' => 'TSR payment covered by the contract',
                'icon' => 'ri-secure-payment-line',
                'color' => 'bg-primary-subtle text-primary'
            ];
        });

        $rating = User::where('is_active', 1)
        ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id')
        ->selectRaw('AVG(COALESCE(survey_answers.rating, 0)) as overall_rating')
        ->value('overall_rating');
        $ratingPercentage = round(($rating / 5) * 100, 2);

        return [
            'answered' => SurveyAnswer::where('survey_id', $id)->distinct('user_id')->count('user_id'),
            'active' => User::where('is_active',1)->count(),
            'rating' => $ratingPercentage . '%',
            'statuses' => $statuses
        ];
    }

    public function question(){
        $body = SurveyQuestion::leftJoin('survey_answers', 'survey_questions.id', '=', 'survey_answers.question_id')
        ->selectRaw('
            survey_questions.id, 
            survey_questions.question as name, 
            COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
            COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
            COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
            COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
            COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
            COUNT(survey_answers.id) AS total
        ')
        ->groupBy('survey_questions.id', 'survey_questions.question')
        ->get();

        $footer = [
            'dn' => $body->sum('dn'),
            'n' => $body->sum('n'),
            'ns' => $body->sum('ns'),
            'y' => $body->sum('y'),
            'dy' => $body->sum('dy'),
            'total' => $body->sum('total'),
        ];
        return [
            'body' => $body,
            'footer' => $footer
        ];
    }

    public function station(){
        $body = ListDropdown::where('classification','Station')// Start from list_dropdowns (station table)
        ->leftJoin('user_organizations', 'list_dropdowns.id', '=', 'user_organizations.station_id') // Join user_organizations
        ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id') // Join users
        ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id') // Join survey_answers
        ->leftJoin('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.id') // Join survey_questions
        ->selectRaw('
            list_dropdowns.name as name, 
            COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
            COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
            COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
            COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
            COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
            COUNT(survey_answers.id) AS total
        ')
        ->groupBy('list_dropdowns.name')
        ->orderBy('list_dropdowns.name')
        ->get();
    

        $footer = [
            'dn' => $body->sum('dn'),
            'n' => $body->sum('n'),
            'ns' => $body->sum('ns'),
            'y' => $body->sum('y'),
            'dy' => $body->sum('dy'),
            'total' => $body->sum('total'),
        ];
        return [
            'body' => $body,
            'footer' => $footer
        ];
    }

    public function division(){
        $body = ListDropdown::where('classification','Division')// Start from list_dropdowns (station table)
        ->leftJoin('user_organizations', 'list_dropdowns.id', '=', 'user_organizations.division_id') // Join user_organizations
        ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id') // Join users
        ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id') // Join survey_answers
        ->leftJoin('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.id') // Join survey_questions
        ->selectRaw('
            list_dropdowns.name as name, 
            COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
            COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
            COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
            COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
            COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
            COUNT(survey_answers.id) AS total
        ')
        ->groupBy('list_dropdowns.name')
        ->orderBy('list_dropdowns.name')
        ->get();
    

        $footer = [
            'dn' => $body->sum('dn'),
            'n' => $body->sum('n'),
            'ns' => $body->sum('ns'),
            'y' => $body->sum('y'),
            'dy' => $body->sum('dy'),
            'total' => $body->sum('total'),
        ];
        return [
            'body' => $body,
            'footer' => $footer
        ];
    }

    public function unit($request){
        $body = ListUnit::where('list_units.is_active',1)
        ->leftJoin('user_organizations', 'list_units.id', '=', 'user_organizations.unit_id') 
        ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id') 
        ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id') 
        ->leftJoin('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.id')
        ->when($request->division, function ($query, $division) {
            $query->where('list_units.division_id',$division);
        })
        ->selectRaw('
            list_units.name as name, 
            COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
            COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
            COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
            COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
            COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
            COUNT(survey_answers.id) AS total
        ')
        ->groupBy('list_units.name')
        ->orderBy('list_units.name')
        ->get();
    

        $footer = [
            'dn' => $body->sum('dn'),
            'n' => $body->sum('n'),
            'ns' => $body->sum('ns'),
            'y' => $body->sum('y'),
            'dy' => $body->sum('dy'),
            'total' => $body->sum('total'),
        ];
        return [
            'body' => $body,
            'footer' => $footer
        ];
    }
}
