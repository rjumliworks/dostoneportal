<?php

namespace App\Services\Portal\Whereabouts;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\ListDropdown;
use App\Models\OrgChart;
use App\Http\Resources\Portal\Whereabouts\ListResource;

class ViewClass
{
    public function list($request){
        $query = User::select('id', 'email', 'username', 'is_locked', 'is_active', 'created_at')
        ->with([
            'profile.religion',
            'profile.blood',
            'profile.marital',
            'profile.sex',
            'profile.suffix',
            'organization.division',
            'organization.position',
            'organization.unit',
            'organization.station',
            'organization.type',
            'organization.status',
            'organization.salary',
            'organization.shift'
        ]);
        $query->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('profile', function ($sub) use ($keyword) {
                $sub->whereRaw('LOWER(firstname) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->orWhereRaw('LOWER(lastname) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->orWhereRaw('LOWER(middlename) LIKE ?', ['%' . strtolower($keyword) . '%']);
            })
            ->orWhere('username', 'like', "%{$keyword}%");
        });
        $query->whereHas('organization', function ($org) use ($request) {
            $org->when($request->status, fn($q) => $q->where('status_id', $request->status))
                ->when($request->type, fn($q) => $q->where('type_id', $request->type))
                ->when($request->position, fn($q) => $q->where('position_id', $request->position))
                ->when($request->division, fn($q) => $q->where('division_id', $request->division))
                ->when($request->station, fn($q) => $q->where('station_id', $request->station))
                ->when($request->unit, fn($q) => $q->where('unit_id', $request->unit));
        });
        $query->whereNotIn('id', OrgChart::excludedFromAttendance());
        $query->orderBy(UserProfile::select('lastname')->whereColumn('user_profiles.user_id', 'users.id'),'ASC');
        $data = ListResource::collection($query->get());
        return $data;
    }

    public function dropdowns(){
        $data = ListDropdown::with('assigned.user.profile','assigned.oic.profile','assigned.designation','assigned.designationable.user.profile','assigned.designationable.oic.profile')
        ->where('classification','Division')
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others,
                'assigned' => $item->assigned
            ];
        });
        return $data;
    }
}
