<?php

namespace App\Services\Executive\ShiftRotation;

use App\Models\User;
use App\Models\ShiftRotation;
use App\Http\Resources\Executive\ShiftRotationResource;

class ViewClass
{
    public function lists()
    {
        $data = ShiftRotation::with(['user.profile', 'user.organization.position', 'user.organization.shift'])
            ->orderBy('order')
            ->get();

        return ShiftRotationResource::collection($data);
    }

    public function guards()
    {
        return User::whereHas('organization.division', function ($query) {
                $query->where('name', 'Security Guards');
            })
            ->with('profile')
            ->get()
            ->map(function ($user) {
                return [
                    'value' => $user->id,
                    'name' => $user->profile?->name ?? $user->username,
                ];
            });
    }
}
