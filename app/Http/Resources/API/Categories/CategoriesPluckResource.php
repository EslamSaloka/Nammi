<?php

namespace App\Http\Resources\API\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesPluckResource extends JsonResource
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
            "background_color"  => $this->hexacode_color,
            "image"     => $this->display_image,
            "children"  => CategoriesResource::collection($this->children),
        ];
    }
}
