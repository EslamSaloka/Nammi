<?php

namespace App\Http\Resources\API\Banner;

use App\Http\Resources\API\Club\ClubActivityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            "display_image"     => $this->display_image,
            "activity"       => new ClubActivityResource($this->activity),
        ];
    }
}
