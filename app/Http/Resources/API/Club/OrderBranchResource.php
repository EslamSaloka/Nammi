<?php

namespace App\Http\Resources\API\Club;

use App\Http\Resources\API\City\CityResource;
use App\Http\Resources\API\Country\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderBranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int)$this->id,
            'name'          => $this->name,
            'address'       => $this->address,
            'country'       => (is_null($this->country)) ? [] : new CountryResource($this->country),
            'city'          => (is_null($this->city)) ? [] : new CityResource($this->city),
        ];
    }
}
