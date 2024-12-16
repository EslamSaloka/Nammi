<?php

namespace App\Http\Controllers\API\Chat;

// Controllers
use App\Http\Controllers\Controller;
// Http
use App\Http\Requests\API\Chat\ChatRequests;
use App\Http\Requests\API\Chat\ChatSearchRequests;
// Models
use App\Models\Chat;
use App\Models\Chat\Message as ChatMessage;
// Support
use App\Support\API;
// Resources
use App\Http\Resources\API\Chat\ChatResources;
use App\Http\Resources\API\Chat\MessageResources;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index() {

        $chats = Chat::where("customer_id",Auth::user()->id)->orderBy("updated_at","desc")->get();


        $new_messages = Chat::select("id")->where("customer_id",Auth::user()->id)
                    ->whereHas("messages",function($q) {
                        return $q->where("seen","=",0)->where("customer_id","!=",Auth::user()->id);
                    })->count();
        return (new API)->isOk(__("Chat lists"))
        ->setData(ChatResources::collection($chats))
        ->addAttribute("new_messages",$new_messages)->build();
    }

    public function show(Chat $chat) {
        if($chat->customer_id != Auth::user()->id) {
            return (new API)->isError(__("You don't have permission to view this conversation"))->setErrors([
                "chat"  => __("You don't have permission to view this conversation")
            ])->build();
        }
        $messages = $chat->messages()->orderBy("id","desc")->paginate();
        $user = [
            "id"                => (int)$chat->club_id,
            "name"              => $chat->club->name,
            "avatar"            => $chat->club->display_image,
            "last_action_at"    => (is_null($chat->club->last_action_at)) ? '': \Carbon\Carbon::parse($chat->club->last_action_at)->diffForHumans() ?? '',
        ];
        $chat->messages()->where("user_id","!=",Auth::user()->id)->update([
            "seen"  => 1
        ]);
        return (new API)->isOk(__("View conversation"))
            ->setData(MessageResources::collection($messages))
            ->addAttribute("club_receiver",$user)
            ->addAttribute("paginate",api_model_set_paginate($messages))
            ->build();
    }

    public function store(ChatRequests $request) {

        $club = \App\Models\User::find($request->club_id);
        if(is_null($club) || !in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new API)->isError(__("This Club Not Found"))->setErrors([
                "club_id"   => __("This Club Not Found")
            ])->build();
        }

        $chat = Chat::where("customer_id",Auth::user()->id)->where("club_id",$request->club_id)->first();

        if(is_null($chat)) {

            $chat = Chat::create([
                "customer_id"   => Auth::user()->id,
                "club_id"       => $club->id,
            ]);
        }

        $chat->messages()->create([
            "user_id"   => Auth::user()->id,
            "message"   => $request->message,
        ]);

        $chat->update([
            "updated_at"    => \Carbon\Carbon::now()
        ]);

        (new \App\Support\Notification)->setPushNotification(true)->setTo($chat->club_id)->setTarget($chat->id)->setTargetType("chat")->setBody(request("message"))->build();

        return (new API)->isOk(__("Send Message Successful"))->setData([
            "chat_id"    => $chat->id
        ])->build();
    }

    public function delete(Chat $chat,ChatMessage $message) {
        if($message->user_id != Auth::user()->id) {
            return (new API)->isError(__("You do not have permission to delete this message"))->build();
        }
        if($message->chat_id != $chat->id) {
            return (new API)->isError(__("This message does not exist for this chat"))->build();
        }
        $message->delete();
        return (new API)->isOk(__("message deleted"))->build();
    }

    public function search(ChatSearchRequests $request) {
        $club = User::whereHas("roles",function($r){
            return $r->where("name",User::TYPE_CLUB);
        })->where("name","like","%".$request->name."%")->first();
        if(is_null($club)) {
            return (new API)->isError(__("This club does not exist"))->setErrors([
                "name"  => __("This club does not exist")
            ])->build();
        }
        $chat = Chat::where([
            "customer_id"    =>  Auth::user()->id,
            "club_id"   =>  $club->id,
            ])->first();
        if(is_null($chat)) {
            return (new API)->isError(__("There are no conversations"))->build();
        }
        return (new API)->isOk(__("View conversation"))->setData(new ChatResources($chat))->build();
    }
}
