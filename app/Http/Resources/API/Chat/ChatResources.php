<?php

namespace App\Http\Resources\API\Chat;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $last        = $this->messages()->orderBy("id","desc")->first();
        return [
            "id"                => $this->id,
            "club"              => [
                "id"                    => $this->club_id,
                "name"                  => $this->club->name,
                "avatar"                => $this->club->display_image,
                "last_action_at"        => (is_null($this->club->last_action_at)) ? '': \Carbon\Carbon::parse($this->club->last_action_at)->diffForHumans() ?? '',
            ],
            "last_message"      => [
                "sendBy"    => (Auth::user()->id == $last->user_id) ? "you" : $last->user->name,
                "message"   => $last->message,
                "seen"      => ($last->seen == 1) ? true : false,
            ],
            "lastDate"          => $last->created_at->diffForHumans(),
        ];
    }
}
