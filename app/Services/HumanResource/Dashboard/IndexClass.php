<?php

namespace App\Services\HumanResource\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ListData;
use App\Models\RequestDate;
use App\Models\UserOrganization;

class IndexClass
{
    public function __construct()
    {
        $this->start = now()->format('Y-m-d');
        $this->end = now()->copy()->addMonth()->format('Y-m-d');
    }

    public function counts(){
        return [
            $this->travel(),
            $this->business(),
            $this->leave(),
        ];
    }

    private function travel()
    {
        $expanded = [];
        $records = RequestDate::select('start', 'end')
        ->whereHas('request',function ($query){
            $query->where('type_id',156);
        })
        ->where(function ($q) {
            $q->whereBetween(\DB::raw('DATE(start)'), [$this->start, $this->end])
            ->orWhereBetween(\DB::raw('DATE(end)'), [$this->start, $this->end]);
        })
        ->get();

        foreach ($records as $row) {
            $start = Carbon::parse($row->start);
            $end   = Carbon::parse($row->end);
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $day = $cursor->format('Y-m-d');

                if (!isset($expanded[$day])) {
                    $expanded[$day] = 0;
                }
                $expanded[$day]++;

                $cursor->addDay();
            }
        }
        $start = Carbon::parse($this->start);
        $end   = Carbon::parse($this->end);
        $data = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $day = $cursor->format('Y-m-d');

            $data[] = [
                'x' => $cursor->format('F d, Y'),
                'y' => $expanded[$day] ?? 0
            ];

            $cursor->addDay();
        }
        $today = now()->format('Y-m-d');
        $totalToday = $expanded[$today] ?? 0;

        return [
            'name' => 'Official Travel',
            'icon' => 'ri-flight-takeoff-fill',
            'color' => '',
            'series' => [
                [
                    'name' => 'Official Travel',
                    'data' => $data
                ]
            ],
            'total' => $totalToday
        ];
    }

    private function business()
    {
        $expanded = [];
        $records = RequestDate::select('start', 'end')
        ->whereHas('request',function ($query){
            $query->where('type_id',192);
        })
        ->where(function ($q) {
            $q->whereBetween(\DB::raw('DATE(start)'), [$this->start, $this->end])
            ->orWhereBetween(\DB::raw('DATE(end)'), [$this->start, $this->end]);
        })
        ->get();

        foreach ($records as $row) {
            $start = Carbon::parse($row->start);
            $end   = Carbon::parse($row->end);
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $day = $cursor->format('Y-m-d');

                if (!isset($expanded[$day])) {
                    $expanded[$day] = 0;
                }
                $expanded[$day]++;

                $cursor->addDay();
            }
        }
        $start = Carbon::parse($this->start);
        $end   = Carbon::parse($this->end);
        $data = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $day = $cursor->format('Y-m-d');

            $data[] = [
                'x' => $cursor->format('F d, Y'),
                'y' => $expanded[$day] ?? 0
            ];

            $cursor->addDay();
        }
        $today = now()->format('Y-m-d');
        $totalToday = $expanded[$today] ?? 0;

        return [
            'name' => 'Official Business',
            'icon' => 'ri-car-fill',
            'color' => '',
            'series' => [
                [
                    'name' => 'Official Business',
                    'data' => $data
                ]
            ],
            'total' => $totalToday
        ];
    }

    private function leave()
    {
        $expanded = [];
        $records = RequestDate::select('start', 'end')
        ->whereHas('request',function ($query){
            $query->where('type_id',158);
        })
        ->where(function ($q) {
            $q->whereBetween(\DB::raw('DATE(start)'), [$this->start, $this->end])
            ->orWhereBetween(\DB::raw('DATE(end)'), [$this->start, $this->end]);
        })
        ->get();

        foreach ($records as $row) {
            $start = Carbon::parse($row->start);
            $end   = Carbon::parse($row->end);
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $day = $cursor->format('Y-m-d');

                if (!isset($expanded[$day])) {
                    $expanded[$day] = 0;
                }
                $expanded[$day]++;

                $cursor->addDay();
            }
        }
        $start = Carbon::parse($this->start);
        $end   = Carbon::parse($this->end);
        $data = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $day = $cursor->format('Y-m-d');

            $data[] = [
                'x' => $cursor->format('F d, Y'),
                'y' => $expanded[$day] ?? 0
            ];

            $cursor->addDay();
        }
        $today = now()->format('Y-m-d');
        $totalToday = $expanded[$today] ?? 0;

        return [
            'name' => 'On Leave',
            'icon' => 'ri-calendar-fill',
            'color' => '',
            'series' => [
                [
                    'name' => 'On Leave',
                    'data' => $data
                ]
            ],
            'total' => $totalToday
        ];
    }



    // public function counts(){
    //     return [
    //         [
    //             'name' => 'Active Employee',
    //             'icon' => 'ri-team-fill',
    //             'color' => 'bg-info-subtle',
    //             'total' => 1
    //         ],
    //         [
    //             'name' => 'Official Travel',
    //             'icon' => 'ri-flight-takeoff-fill',
    //             'color' => '',
    //             'total' => 1
    //         ],
    //          [
    //             'name' => 'Official Business',
    //             'icon' => 'ri-car-fill',
    //             'color' => '',
    //             'total' => 1
    //         ]
            
    //     ];
    // }

    public function employee(){
       
        $statuses = ListData::where('is_active',1)->where('type','Employment Status')->get()->map(function ($item){
            $status = $item->id;
            $totalUsers = User::where('is_active', 1)->count();
            $statusCount = User::where('is_active', 1)
                ->join('user_organizations', 'users.id', '=', 'user_organizations.user_id')
                ->where('user_organizations.type_id', $item->id)
                ->count();

            // Compute percentage based on ALL active users
            $percentage = $totalUsers > 0 
                ? round(($statusCount / $totalUsers) * 100, 2) 
                : 0;

            return [
                'name'  => $item->name,
                'count' => $statusCount,
                'percentage' => $percentage . '%',
                'color' => 'bg-primary-subtle text-primary',
            ];
        });

        $rating = User::where('is_active', 1)
        ->leftJoin('survey_answers', 'users.id', '=', 'survey_answers.user_id')
        ->selectRaw('AVG(COALESCE(survey_answers.rating, 0)) as overall_rating')
        ->value('overall_rating');
        $ratingPercentage = round(($rating / 5) * 100, 2);

        return [
            'absent' => User::whereHas('organization', function ($query){
                $query->where('status_id',2);
            })->count(),
            'present' => User::whereHas('organization', function ($query){
                $query->where('status_id',2);
            })->count(),
            'all' => UserOrganization::where('status_id',2)->count(),
            'statuses' => $statuses
        ];
    }
}
