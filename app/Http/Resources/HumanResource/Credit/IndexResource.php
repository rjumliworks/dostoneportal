<?php

namespace App\Http\Resources\HumanResource\Credit;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'code' => $code,
            'profile' => $this->profile,
            'organization' => $this->organization,
            'created_at' => $this->created_at,
            'credits' => $this->credits,
            'leave_credits' => $this->leaveCredits(),
            'avatar' => $this->profile?->avatar ?? asset('images/avatars/noavatar.jpg'),
        ];
    }

    /**
     * Force Leave, Special Privilege, and Wellness are fixed allotments
     * (not accrual-based), so they all show as remaining/max days for a
     * consistent read. CTO shows a plain balance at the CSC conversion
     * rate of 1 day = 10 hrs. Maternity/Paternity/Study are request-only
     * leaves with no automatic yearly credit, so they only show a value
     * when one was actually granted.
     */
    protected function leaveCredits(): array
    {
        $credits = $this->credits ?? collect();
        $find = fn (int $leaveId) => $credits->firstWhere('leave_id', $leaveId);

        $vacation = $find(1);
        $force = $find(2);
        $sick = $find(3);
        $maternity = $find(4);
        $paternity = $find(5);
        $privilege = $find(6);
        $study = $find(8);
        $wellness = $find(17);
        $cto = $find(14);

        return [
            'vacation' => $vacation ? $this->number($vacation->balance) : 'N/A',
            'vacation_hours' => $vacation ? $this->hours($vacation->balance) : null,
            'sick' => $sick ? $this->number($sick->balance) : 'N/A',
            'sick_hours' => $sick ? $this->hours($sick->balance) : null,
            'force' => $force ? $this->fraction($force->balance, $force->leave->max_days) : 'N/A',
            'force_hours' => $force ? $this->hours($force->balance) : null,
            'special_privilege' => $privilege ? $this->fraction($privilege->balance, $privilege->leave->max_days) : 'N/A',
            'special_privilege_hours' => $privilege ? $this->hours($privilege->balance) : null,
            'maternity' => $maternity ? $this->number($maternity->balance) . ' days' : 'N/A',
            'maternity_hours' => $maternity ? $this->hours($maternity->balance) : null,
            'paternity' => $paternity ? $this->number($paternity->balance) . ' days' : 'N/A',
            'paternity_hours' => $paternity ? $this->hours($paternity->balance) : null,
            'study' => $study ? $this->number($study->balance) . ' days' : 'N/A',
            'study_hours' => $study ? $this->hours($study->balance) : null,
            'wellness' => $wellness ? $this->fraction($wellness->balance, $wellness->leave->max_days) : 'N/A',
            'wellness_hours' => $wellness ? $this->hours($wellness->balance) : null,
            'cto' => $cto ? $this->number($cto->balance) : 'N/A',
            'cto_hours' => $cto ? $this->hours($cto->balance, 10) : null,
        ];
    }

    protected function fraction($numerator, $denominator): string
    {
        return $this->number($numerator) . '/' . $this->number($denominator);
    }

    /**
     * Converts a day balance to its hour equivalent so users can read leave
     * credits either way. Standard workday is 8 hrs; CTO uses the CSC
     * conversion rate of 10 hrs per day. Past a full workday's worth of
     * hours it's compacted into "Xd Yh" (1 day = 8 hrs) so it stays short
     * instead of showing a large raw hour count.
     */
    protected function hours($days, int $hoursPerDay = 8): string
    {
        $totalHours = (float) $days * $hoursPerDay;
        $workday = 8;

        if ($totalHours <= $workday) {
            return '(' . $this->number($totalHours) . 'h)';
        }

        $wholeDays = (int) floor($totalHours / $workday);
        $remainder = round($totalHours - ($wholeDays * $workday), 2);

        if ($remainder <= 0) {
            return '(' . $wholeDays . 'd)';
        }

        return '(' . $wholeDays . 'd ' . $this->number($remainder) . 'h)';
    }

    protected function number($value): string
    {
        $formatted = number_format((float) $value, 2, '.', '');
        $trimmed = rtrim(rtrim($formatted, '0'), '.');

        return $trimmed === '' ? '0' : $trimmed;
    }
}
