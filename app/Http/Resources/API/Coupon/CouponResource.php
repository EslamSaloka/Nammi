<?php

namespace App\Http\Resources\API\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"      => $this->id,
            "name"    => $this->name,
            "value"   => $this->value,
            "type"    => $this->type,
            "expire"  => $this->expire,
        ];
    }
}