<?php

namespace App\Console\Commands;

use App\Models\ShiftRotation;
use App\Models\UserOrganization;
use Illuminate\Console\Command;

class RotateGuardShiftsCommand extends Command
{
    protected $signature = 'shift:rotate-guards';
    protected $description = 'Weekly rotation of the security guard shifts: morning -> afternoon -> night -> morning';

    // shift_id => next shift_id (Security Morning=4, Security Afternoon=5, Security Night=6).
    // The guards themselves never change order - only which shift_id each one currently
    // holds advances by one slot. Who is currently in the rotation is managed in the
    // "Guard Shift Rotation" screen under Executive (the shift_rotations table).
    const ROTATION = [
        4 => 5,
        5 => 6,
        6 => 4,
    ];

    public function handle()
    {
        $guardUserIds = ShiftRotation::where('is_active', 1)->pluck('user_id');

        $organizations = UserOrganization::whereIn('user_id', $guardUserIds)
            ->whereIn('shift_id', array_keys(self::ROTATION))
            ->get();

        foreach ($organizations as $organization) {
            $next = self::ROTATION[$organization->shift_id];
            $this->info("User {$organization->user_id}: shift {$organization->shift_id} -> {$next}");
            $organization->update(['shift_id' => $next]);
        }

        $this->info('Guard shift rotation complete.');
    }
}
