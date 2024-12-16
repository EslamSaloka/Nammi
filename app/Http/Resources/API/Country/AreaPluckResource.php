<?php

namespace App\Http\Resources\API\Country;

use App\Http\Resources\API\City\CityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaPluckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "cities"    => CityResource::collection($this->cities)
        ];
    }
}
