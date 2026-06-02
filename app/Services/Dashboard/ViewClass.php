<?php

namespace App\Services\Dashboard;

use App\Models\Dtr;
use App\Models\OrgChart;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HumanResource\Dtr\IndexResource;
use App\Http\Resources\Executive\Signatory\ListResource;

class ViewClass
{
    public function birthdays(){
        return UserProfile::where('birthmonth', date('m'))
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'avatar' => $user->avatar,
                'fullname' => $user->fullname,
                'birthdate' => $user->birthdate,
            ];
        });
    }

    public function dtr(){
        $dtr = Dtr::where('user_id', Auth::id())
        ->whereDate('created_at', date('Y-m-d'))
        ->first();

        return $dtr ? new IndexResource($dtr) : null;
    }

     public function designations(){
        $data = OrgChart::with('designation','assigned')
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar')
        ->whereIn('id',[1,2,3])
        ->orderBy('order','ASC')
        ->get();
        return ListResource::collection($data);
    }
}
