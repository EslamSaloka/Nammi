<?php

namespace App\Http\Controllers\Dashboard\Chats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Chats\CreateMessageRequest;
// Http
use Illuminate\Http\Request;
// Models
use App\Models\Chat;
use Carbon\Carbon;
// Support
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Chats lists"),
            'items' =>  [
                [
                    'title' =>  __("Chats lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];

        return view('admin.pages.chats.index',[
            "breadcrumb"    => $breadcrumb,
            "lists"         => $this->chats(),
        ]);
    }

    public function show(Chat $chat)
    {
        if($chat->club_id != Auth::user()->id) {
            abort(403);
        }
        $breadcrumb = [
            'title' =>  __("Chats Messages"),
            'items' =>  [
                [
                    'title' =>  __("Chats lists"),
                    'url'   =>  route("admin.chats.index"),
                ],
                [
                    'title' =>  __("Chats Messages"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $chat->messages()->update([
            "seen"=>1
        ]);
        $chat->update([
            "updated_at"=> Carbon::now()
        ]);
        return view('admin.pages.chats.index',[
            "breadcrumb"    => $breadcrumb,
            "lists"         => $this->chats(),
            "chat"          => $chat,
        ]);
    }

    public function update(CreateMessageRequest $request,Chat $chat) {
        $chat->messages()->create([
            "user_id"   => Auth::user()->id,
            "message"   => request("message"),
        ]);
        $chat->update([
            "updated_at"=> Carbon::now()
        ]);
        // (new \App\Support\Notification)->setPushNotification(true)->setTo($chat->customer_id)->setTarget($chat->id)->setTargetType("chat")->setBody(request("message"))->build();
        return redirect()->back()->with('success', __("Your Message Has Been Send") );
    }

    private function chats() {
        return Chat::where("club_id",Auth::user()->id)->orderBy("updated_at","desc")->paginate();
    }
}
