<?php

namespace App\Http\Resources\API\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            'body'          => $this->body,
            'target'        => $this->target,
            'target_id'     => $this->target_id,
            'seen'          => ($this->seen == true) ? true : false,
            "created_at"    => $this->created_at->diffForHumans(),
        ];
    }
}
