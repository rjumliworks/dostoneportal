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

    public function counts($code)
    {
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($code)[0];

        $statuses = ListData::where('is_active', 1)
            ->where('type', 'Employment Status')
            ->get()
            ->map(function ($item) use ($id) {

                $result = User::where('users.is_active', 1)
                    ->join('user_organizations', 'users.id', '=', 'user_organizations.user_id')
                    ->where('user_organizations.type_id', $item->id)
                    ->leftJoin('survey_answers', function ($join) use ($id) {
                        $join->on('users.id', '=', 'survey_answers.user_id')
                            ->where('survey_answers.survey_id', $id);
                    })
                    ->selectRaw("
                        COALESCE(SUM(survey_answers.rating), 0) AS score,
                        COUNT(survey_answers.id) AS total,
                        COUNT(DISTINCT survey_answers.user_id) AS respondents,
                        COUNT(DISTINCT users.id) AS eligible
                    ")
                    ->first();

                $ratingPercentage = $result->total
                    ? round(($result->score / ($result->total * 5)) * 100, 2)
                    : 0;

                return [
                    'name' => $item->name,
                    'count' => $ratingPercentage . '%',
                    'respondents' => $result->respondents,
                    'eligible' => $result->eligible,
                    'description' => 'Overall survey rating',
                    'icon' => 'ri-secure-payment-line',
                    'color' => 'bg-primary-subtle text-primary'
                ];
            });

        $overall = User::where('users.is_active', 1)
            ->leftJoin('survey_answers', function ($join) use ($id) {
                $join->on('users.id', '=', 'survey_answers.user_id')
                    ->where('survey_answers.survey_id', $id);
            })
            ->selectRaw("
                COALESCE(SUM(survey_answers.rating), 0) AS score,
                COUNT(survey_answers.id) AS total
            ")
            ->first();

        $ratingPercentage = $overall->total
            ? round(($overall->score / ($overall->total * 5)) * 100, 2)
            : 0;

        return [
            'answered' => SurveyAnswer::where('survey_id', $id)
                ->distinct('user_id')
                ->count('user_id'),

            'active' => User::where('is_active', 1)->count(),

            'rating' => $ratingPercentage . '%',

            'statuses' => $statuses,
        ];
    }

    public function question()
    {
        $survey_id = $this->activeSurveyId();
        $eligible = User::where('is_active', 1)->count();
        $body = SurveyQuestion::leftJoin('survey_answers', function ($join) use ($survey_id) {
            $join->on('survey_questions.id', '=', 'survey_answers.question_id')
                ->where('survey_answers.survey_id', $survey_id);
        })
        ->selectRaw("
            survey_questions.id,
            survey_questions.question AS name,
            COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
            COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
            COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
            COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
            COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
            COUNT(survey_answers.id) AS answers,
            COUNT(DISTINCT survey_answers.user_id) AS respondents,
            COALESCE(SUM(survey_answers.rating),0) AS score,
            ROUND(
                COALESCE(SUM(survey_answers.rating),0) /
                NULLIF(COUNT(survey_answers.id) * 5,0) * 100,
                2
            ) AS percentage
        ")
        ->groupBy('survey_questions.id', 'survey_questions.question')
        ->get();

        $body->each(function ($row) use ($eligible) {
            $row->eligible = $eligible;
        });

        return $this->addSummary($body);
    }

    public function station()
    {
        $survey_id = $this->activeSurveyId();
        $body = ListDropdown::where('classification', 'Station')
            ->leftJoin('user_organizations', 'list_dropdowns.id', '=', 'user_organizations.station_id')
            ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id')
            ->leftJoin('survey_answers', function ($join) use ($survey_id) {
                $join->on('users.id', '=', 'survey_answers.user_id')
                    ->where('survey_answers.survey_id', $survey_id);
            })
            ->selectRaw("
                list_dropdowns.name AS name,
                COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
                COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
                COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
                COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
                COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
                COUNT(survey_answers.id) AS answers,
                COUNT(DISTINCT survey_answers.user_id) AS respondents,
                COUNT(DISTINCT CASE WHEN users.is_active = 1 THEN users.id END) AS eligible,
                COALESCE(SUM(survey_answers.rating),0) AS score,
                ROUND(
                    COALESCE(SUM(survey_answers.rating),0) /
                    NULLIF(COUNT(survey_answers.id) * 5,0) * 100,
                    2
                ) AS percentage
            ")
            ->groupBy('list_dropdowns.name')
            ->orderBy('list_dropdowns.name')
            ->get();

        return $this->addSummary($body);
    }

    public function division()
    {
        $survey_id = $this->activeSurveyId();
        $body = ListDropdown::where('classification', 'Division')
            ->leftJoin('user_organizations', 'list_dropdowns.id', '=', 'user_organizations.division_id')
            ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id')
            ->leftJoin('survey_answers', function ($join) use ($survey_id) {
                $join->on('users.id', '=', 'survey_answers.user_id')
                    ->where('survey_answers.survey_id', $survey_id);
            })
            ->selectRaw("
                list_dropdowns.name AS name,
                COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
                COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
                COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
                COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
                COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
                COUNT(survey_answers.id) AS answers,
                COUNT(DISTINCT survey_answers.user_id) AS respondents,
                COUNT(DISTINCT CASE WHEN users.is_active = 1 THEN users.id END) AS eligible,
                COALESCE(SUM(survey_answers.rating),0) AS score,
                ROUND(
                    COALESCE(SUM(survey_answers.rating),0) /
                    NULLIF(COUNT(survey_answers.id) * 5,0) * 100,
                    2
                ) AS percentage
            ")
            ->groupBy('list_dropdowns.name')
            ->orderBy('list_dropdowns.name')
            ->get();

        return $this->addSummary($body);
    }

    public function unit($request)
    {
        $survey_id = $this->activeSurveyId();
        $body = ListUnit::where('list_units.is_active', 1)
            ->leftJoin('user_organizations', 'list_units.id', '=', 'user_organizations.unit_id')
            ->leftJoin('users', 'user_organizations.user_id', '=', 'users.id')
            ->leftJoin('survey_answers', function ($join) use ($survey_id) {
                $join->on('users.id', '=', 'survey_answers.user_id')
                    ->where('survey_answers.survey_id', $survey_id);
            })
            ->when($request->division, function ($query, $division) {
                $query->where('list_units.division_id', $division);
            })
            ->selectRaw("
                list_units.id,
                list_units.name AS name,
                COUNT(CASE WHEN survey_answers.rating = 1 THEN 1 END) AS dn,
                COUNT(CASE WHEN survey_answers.rating = 2 THEN 1 END) AS n,
                COUNT(CASE WHEN survey_answers.rating = 3 THEN 1 END) AS ns,
                COUNT(CASE WHEN survey_answers.rating = 4 THEN 1 END) AS y,
                COUNT(CASE WHEN survey_answers.rating = 5 THEN 1 END) AS dy,
                COUNT(survey_answers.id) AS answers,
                COUNT(DISTINCT survey_answers.user_id) AS respondents,
                COUNT(DISTINCT CASE WHEN users.is_active = 1 THEN users.id END) AS eligible,
                COALESCE(SUM(survey_answers.rating),0) AS score,
                ROUND(
                    COALESCE(SUM(survey_answers.rating),0) /
                    NULLIF(COUNT(survey_answers.id) * 5,0) * 100,
                    2
                ) AS percentage
            ")
            ->groupBy('list_units.id', 'list_units.name')
            ->orderBy('list_units.name')
            ->get();

            
             // or your survey id variable

            $body->each(function ($item) use ($survey_id) {

                $item->not_submitted = User::where('users.is_active', 1)
                    ->join('user_organizations', 'users.id', '=', 'user_organizations.user_id')
                    ->where('user_organizations.unit_id', $item->id)
                    ->whereNotExists(function ($query) use ($survey_id) {
                        $query->selectRaw(1)
                            ->from('survey_answers')
                            ->whereColumn('survey_answers.user_id', 'users.id')
                            ->where('survey_answers.survey_id', $survey_id);
                    })
                    ->pluck('users.username')
                    ->values();
            });

        

        return $this->addSummary($body);
    }

    private function addSummary($body)
    {
        $footer = [
            'dn' => $body->sum('dn'),
            'n' => $body->sum('n'),
            'ns' => $body->sum('ns'),
            'y' => $body->sum('y'),
            'dy' => $body->sum('dy'),

            // answer records
            'answers' => $body->sum('answers'),

            'score' => $body->sum('score'),
        ];

        $footer['percentage'] = $footer['answers']
            ? round(($footer['score'] / ($footer['answers'] * 5)) * 100, 2)
            : 0;

        return [
            'body' => $body,
            'footer' => $footer
        ];
    }

    private function activeSurveyId()
    {
        return Survey::where('is_active', 1)->value('id');
    }
}
