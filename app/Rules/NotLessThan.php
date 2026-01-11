<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotLessThan implements ValidationRule
{
    protected $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === '₱0.00') {
            $fail("The {$attribute} must not be ₱0.00.");
        }else if($value === '0' || $value === 0){
            $fail("The {$attribute} must not be 0.");
        }else{
            $amount = str_replace('₱', '', trim($value));
            $total = str_replace('₱', '', trim($this->total));
            if($amount < $total){
                $fail("Amount must be at least the {$this->total}.");
            }
        }
    }
}
