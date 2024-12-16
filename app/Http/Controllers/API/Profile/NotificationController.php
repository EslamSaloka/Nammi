<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Notification\NotificationResources;
// Models
use App\Models\Notification;
// Support
use Illuminate\Support\Facades\Auth;
use App\Support\API;

class NotificationController extends Controller
{
    public function index() {
        $notifications = Auth::user()->notifications()->orderBy("id","desc")->paginate();
        return (new API)->isOk(__("Notifications lists"))->setData(NotificationResources::collection($notifications))->addAttribute("paginate",api_model_set_paginate($notifications))->build();
    }

    public function show(Notification $notification) {
        if($notification->user_id != Auth::user()->id) {
            return (new API)->isError(__("This notification not for you"))->build();
        }
        if($notification->seen == 0) {
            $notification->update([
                "seen"  => 1
            ]);
        }
        return (new API)->isOk(__("This notification seen"))->addAttribute("notifications_count",Auth::user()->notifications()->where("seen",0)->count())->build();
    }

    public function destroy(Notification $notification) {
        if($notification->user_id != Auth::user()->id) {
            return (new API)->isError(__("This notification not for you"))->build();
        }
        $notification->delete();
        $notiCount = Notification::where("user_id",Auth::user()->id)->where("seen",0)->count();
        return (new API)->isOk(__("Deleted"))->addAttribute("notifications_count",$notiCount)->build();
    }

    // ================================================= //

    public function readAll() {
        Notification::where("user_id",Auth::user()->id)->update([
            "seen"  => 1
            ]);
        $notiCount = Notification::where("user_id",Auth::user()->id)->where("seen",0)->count();
        return (new API)->isOk(__("All notifications has been seen"))->addAttribute("notifications_count",$notiCount)->build();
    }

    public function destroyAll() {
        Notification::where("user_id",Auth::user()->id)->delete();
        $notiCount = Notification::where("user_id",Auth::user()->id)->where("seen",0)->count();
        return (new API)->isOk(__("Deleted"))->addAttribute("notifications_count",$notiCount)->build();
    }
}
