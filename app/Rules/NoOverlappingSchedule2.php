<?php

namespace App\Rules;

use Closure;
use App\Models\OrgSignatorySchedule;
use Illuminate\Contracts\Validation\ValidationRule;

class NoOverlappingSchedule2 implements ValidationRule
{
    protected $signatory_id;
    protected $start_at;
    protected $end_at;

    public function __construct($signatory_id, $start_at, $end_at)
    {
        $this->signatory_id = $signatory_id;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hasOverlap = OrgSignatorySchedule::where('signatory_id', $this->signatory_id)
            ->where(function ($query) {
                $query->whereBetween('start_at', [$this->start_at, $this->end_at])
                    ->orWhereBetween('end_at', [$this->start_at, $this->end_at])
                    ->orWhere(function ($sub) {
                        $sub->where('start_at', '<=', $this->start_at)
                            ->where('end_at', '>=', $this->end_at);
                    });
            })
            // Optional: ignore completed schedules
            ->where('is_completed', 0)
            ->where('is_designated', 1)
            ->exists();

        if ($hasOverlap) {
            $fail('This schedule overlaps with another existing schedule for this signatory.');
        }

        $hasNonDesignated = OrgSignatorySchedule::where('signatory_id', $this->signatory_id)
            ->where('is_designated', 0)
            ->where('is_completed', 0)
            ->exists();

        if ($hasNonDesignated) {
            $fail('This signatory already has an active non-designated schedule.');
            return;
        }
    }
}
