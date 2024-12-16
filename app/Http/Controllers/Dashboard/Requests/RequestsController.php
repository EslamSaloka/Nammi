<?php

namespace App\Http\Controllers\Dashboard\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\NewClub\AcceptedRequest;
use App\Http\Requests\Dashboard\NewClub\RejectedRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Requests lists"),
            'items' =>  [
                [
                    'title' =>  __("Requests lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = User::whereHas('roles', function($q) {
            return $q->where('name', '=', User::TYPE_CLUB);
        })->whereNull("accepted_at")->whereNull("rejected_at")->latest()->paginate();
        return view('admin.pages.requests.index',compact('breadcrumb', 'lists'));
    }

    public function show(User $request)
    {
        $breadcrumb = [
            'title' =>  __("Show Club Information Request"),
            'items' =>  [
                [
                    'title' =>  __("Requests lists"),
                    'url'   =>  route("admin.requests.index"),
                ],
                [
                    'title' =>  __("Show Club Information Request"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.requests.show',compact('breadcrumb', 'request'));
    }

    public function destroy(User $request)
    {
        $request->delete();
        return redirect()->route('admin.requests.index')->with('success', __(":item has been deleted.",['item' => __('club')]) );
    }

    public function accept(AcceptedRequest $request2,User $request) {
        $request->update([
            "accepted_at"   => Carbon::now(),
            "vat"           => $request2->vat
        ]);
        (new \App\Support\Notification)->setTo($request->id)->setTarget($request->id)->setTargetType("accept_club")->setBody(__(":item has been Accepted.",['item' => __('club')]))->build();
        return redirect()->route('admin.requests.index')->with('success', __(":item has been Accepted.",['item' => __('club')]) );
    }

    public function reject(RejectedRequest $request2,User $request) {
        $request->update([
            "rejected_at"                => Carbon::now(),
            "rejected_message"           => $request2->rejected_message
        ]);
        (new \App\Support\Notification)->setTo($request->id)->setTarget($request->id)->setTargetType("reject_club")->setBody(__(":item has been Rejected.",['item' => __('club')]))->build();
        return redirect()->route('admin.requests.index')->with('success', __(":item has been rejected.",['item' => __('club')]) );
    }
}
