<?php

namespace App\Http\Resources\API\Offer;

use App\Http\Resources\API\Club\ClubActivityResource;
use App\Http\Resources\API\Club\ShortClubResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "activity"  => new ClubActivityResource($this),
            "club"      => new ShortClubResource($this->club)
        ];
    }
}
