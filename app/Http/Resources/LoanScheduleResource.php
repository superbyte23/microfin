<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'installment_number' => $this->installment_number,
            'due_date' => $this->due_date->toDateString(),
            'principal' => (float) $this->principal,
            'interest' => (float) $this->interest,
            'total_due' => (float) $this->total_due,
            'paid_amount' => (float) $this->paid_amount,
            'paid_date' => $this->paid_date?->toDateString(),
            'status' => $this->status,
        ];
    }
}
