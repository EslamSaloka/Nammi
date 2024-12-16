<?php

namespace App\Http\Resources\API\Chat;

use App\Models\Post;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResources extends JsonResource
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
            "id"                => $this->id,
            "user"              => [
                "id"        => $this->user_id,
                "name"      => $this->user->name,
                "avatar"    => $this->user->display_image,
            ],
            "message"           => $this->message ?? '',
            "sendByMe"          => ($this->user_id == Auth::user()->id) ? true : false ,
            "created_at"        => $this->created_at->diffForHumans(),
        ];
    }
}
