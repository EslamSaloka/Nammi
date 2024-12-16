<?php

namespace App\Http\Resources\API\Club;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderClubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'rates'         => $this->rates,
            'images'        => ClubImageResource::collection($this->clubImages),
            "is_fav"        => isFav(((\Auth::check()) ? \Auth::user() : null),\App\Models\User::find($this->id)),
        ];
    }
}
