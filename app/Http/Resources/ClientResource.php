<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'branch' => $this->whenLoaded('branch', fn () => [
                'name' => $this->branch?->name,
            ]),
            'status' => $this->status,
        ];
    }
}
