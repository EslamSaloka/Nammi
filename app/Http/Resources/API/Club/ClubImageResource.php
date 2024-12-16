<?php

namespace App\Http\Resources\API\Club;

// Http
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'image'          => $this->display_image,
        ];
    }
}
