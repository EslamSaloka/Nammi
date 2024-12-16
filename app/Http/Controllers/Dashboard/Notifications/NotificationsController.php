<?php

namespace App\Http\Controllers\Dashboard\Notifications;

use App\Http\Controllers\Controller;
// Models
use App\Models\Notification;
use App\Models\User;
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Notifications\NotificationsRequest;
// Support
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Notifications lists"),
            'items' =>  [
                [
                    'title' =>  __("Notifications lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $users = [];
        if(isAdmin()) {
            $lists = [];
            $users = User::whereHas("roles",function($q){
                return $q->where("name",User::TYPE_CLUB)->orWhere("name",User::TYPE_CUSTOMER);
            })->get();
        } else {
            $lists = Notification::where("user_id",Auth::user()->id)->latest()->paginate();
        }
        return view('admin.pages.notifications.index',compact('breadcrumb', 'lists',"users"));
    }

    public function store(NotificationsRequest $request) {
        if(count(request("users",[])) > 0) {
            $users = User::whereHas("roles",function($q){
                return $q->where("name",User::TYPE_CLUB)->orWhere("name",User::TYPE_CUSTOMER);
            })->whereIn("id",request("users",[]))->get();
        } else {
            $users = User::whereHas("roles",function($q){
                return $q->where("name",User::TYPE_CLUB)->orWhere("name",User::TYPE_CUSTOMER);
            })->get();
        }
        foreach($users as $user) {
            (new \App\Support\Notification)->setPushNotification(true)->setTo($user->id)->setTarget(0)->setTargetType("notification")->setBody(request("body"))->build();
        }
        return redirect()->route('admin.notifications.index')->with('success', __(":item has been created.",['item' => __('Notification')]) );
    }

    public function show(Request $request,Notification $notification)
    {
        $notification->update([
            "seen"  => 1
        ]);
        return redirect($notification->getWebNotification()["route"]);
    }

    public function destroy(Request $request,Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', __(":item has been deleted.",['item' => __('Notification')]) );
    }
}
