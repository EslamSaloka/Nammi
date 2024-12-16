<?php

namespace App\Http\Resources\API\Club;

// Http

use App\Http\Resources\API\Customer\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer'       => new CustomerResource($this->customer),
            "rate"           => $this->rate,
            "notes"          => $this->notes ?? '',
        ];
    }
}
