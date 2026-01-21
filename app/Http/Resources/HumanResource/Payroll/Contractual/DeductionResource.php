<?php

namespace App\Http\Resources\HumanResource\Payroll\Contractual;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeductionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'deduction_id' => $this->deduction->id,
            'amount' => $this->amount,
            'name' => $this->deduction->name,
            'is_regular' => $this->deduction->is_regular,
            'is_contribution' => $this->deduction->is_contribution,
            'is_loan' => $this->deduction->is_loan,
            'is_enrollable' => $this->deduction->is_enrollable,
        ]; 
    }
}
