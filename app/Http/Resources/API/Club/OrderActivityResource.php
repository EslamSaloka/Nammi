<?php

namespace App\Http\Resources\API\Club;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderActivityResource extends JsonResource
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
            'image'         => $this->display_image,
            "rates"             => $this->rates,
            "order_one_time"    => ($this->order_one_time == 0) ? false : true,
            'disabilities'      => ($this->disabilities == 0) ? false : true,
        ];
    }
}
