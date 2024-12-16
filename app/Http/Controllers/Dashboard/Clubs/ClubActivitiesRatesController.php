<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Http\Controllers\Controller;
// Http
use Illuminate\Http\Request;
// Models
use App\Models\User;
use App\Models\Activity;
use App\Models\Activity\Rate;
// Support
use Illuminate\Support\Facades\App;

class ClubActivitiesRatesController extends Controller
{

    public function index(Request $request,Activity $activity)
    {
        $breadcrumb = [
            'title' =>  __("Activity rates"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Activities") : __("My Activities"),
                    'url'   => route('admin.clubs.activities.index'),
                ],
                [
                    'title' =>  __("Activity rates"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $lists = $activity->getRates()->latest()->paginate();
        return view('admin.pages.clubs.activities.rates.index',compact('breadcrumb', 'lists',"activity"));
    }

    public function destroy(Request $request,Activity $activity,Rate $rate) {
        $rate->delete();
        return redirect()->route('admin.clubs.activities.rates.index',$activity->id)->with('success', __(":item has been deleted.",['item' => __('Rate')]) );
    }

    public function confirmed(Request $request,Activity $activity,Rate $rate) {
        $rate->update([
            "confirmed" => 1
        ]);
        // ============================ //
        $activateRates = Rate::where(['activity_id' => $activity->id])->sum("rate") / Rate::where(['activity_id' => $activity->id])->count();
        $activity->update([
            "rates" => $activateRates
        ]);
        // ============================ //
        return redirect()->route('admin.clubs.activities.rates.index',$activity->id)->with('success', __(":item has been confirmed.",['item' => __('Rate')]) );
    }
}
