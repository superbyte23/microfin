<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product?->name,
            'principal_amount' => (float) $this->principal_amount,
            'interest_rate' => (float) $this->interest_rate,
            'interest_type' => $this->interest_type,
            'term_months' => $this->term_months,
            'payment_frequency' => $this->payment_frequency,
            'release_date' => $this->release_date?->toDateString(),
            'maturity_date' => $this->maturity_date?->toDateString(),
            'first_payment_date' => $this->first_payment_date?->toDateString(),
            'status' => $this->status,
            'purpose' => $this->purpose,
            'branch' => $this->whenLoaded('branch', fn () => [
                'name' => $this->branch?->name,
            ]),
            'schedule' => LoanScheduleResource::collection($this->whenLoaded('amortizationSchedule')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
