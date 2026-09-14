<?php

namespace App\Services\HumanResource\Credit;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\User;
use App\Models\CreditLog;
use App\Models\UserOrganization;
use App\Http\Resources\HumanResource\Credit\LogResource;
use App\Http\Resources\HumanResource\Credit\IndexResource;
use App\Http\Resources\HumanResource\Credit\ViewResource;

class ViewClass
{
    /**
     * Employment types (list_data ids) an employee can be filtered/counted
     * by on the credits list. Keyed by type_id.
     */
    protected const EMPLOYMENT_TYPES = [
        15 => ['name' => 'Regular', 'icon' => 'ri-user-star-fill'],
        16 => ['name' => 'COS', 'icon' => 'ri-file-user-fill'],
        17 => ['name' => 'Job Order', 'icon' => 'ri-briefcase-fill'],
        18 => ['name' => 'Agency', 'icon' => 'ri-building-fill'],
    ];

    public function lists($request)
    {
        $data = IndexResource::collection(
            User::select('users.id')
                ->with([
                    'profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
                    'organization.position',
                    'organization.status',
                    'credits' => function ($q) {
                        $q->where('year', Carbon::now()->year)->where('is_active',1);
                    },
                    'credits.leave',
                    'credits.logs.type',
                ])
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->when($request->keyword, function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereHas('profile', function ($q2) use ($keyword) {
                            $q2->whereRaw('lastname LIKE ?', ["%{$keyword}%"]);
                        })
                        ->orWhere('username', 'like', "%{$keyword}%");
                    });
                })
                ->whereHas('organization', function ($query) use ($request) {
                    $query->where('status_id', 2)
                        ->when($request->type, fn ($q) => $q->where('type_id', $request->type))
                        ->when($request->division, fn ($q) => $q->where('division_id', $request->division))
                        ->when($request->station, fn ($q) => $q->where('station_id', $request->station))
                        ->when($request->unit, fn ($q) => $q->where('unit_id', $request->unit));
                })
                ->orderBy('user_profiles.lastname', 'ASC')
                ->paginate($request->count)
        );

        return $data;
    }

    /**
     * Employee counts per employment type (Regular/COS/Job Order/Agency),
     * used to drive the filter tabs on the credits list.
     */
    public function counts(): array
    {
        $totals = UserOrganization::where('status_id', 2)
            ->whereIn('type_id', array_keys(self::EMPLOYMENT_TYPES))
            ->selectRaw('type_id, count(*) as total')
            ->groupBy('type_id')
            ->pluck('total', 'type_id');

        return collect(self::EMPLOYMENT_TYPES)
            ->map(fn ($meta, $typeId) => [
                'value' => $typeId,
                'name' => $meta['name'],
                'icon' => $meta['icon'],
                'count' => $totals[$typeId] ?? 0,
            ])
            ->values()
            ->all();
    }

    public function logs($request){
        $data = CreditLog::with('type')->where('credit_id',$request->id)->paginate($request->count);
        return LogResource::collection($data);
    }

    public function view($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = new ViewResource(
            User::query()->select('users.id')
            ->with([
                'profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
                'organization.position',
                'organization.status',
                'credits' => function ($q) {
                    $q->where('year', Carbon::now()->year)->where('is_active',1);
                },
                'credits.leave',
                'credits.logs.type',
            ])
            ->where('id',$id)->first()
        );
        return $data;
    }
}
