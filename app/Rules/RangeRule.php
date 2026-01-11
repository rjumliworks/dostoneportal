<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class RangeRule implements Rule
{
    protected $end;

    public function __construct($end)
    {
        $this->end = $end;
    }

    public function passes($attribute, $value)
    {
        return $value <= $this->end;
    }

    public function message()
    {
        return 'Start must be less than or equal to end.';
    }
}
