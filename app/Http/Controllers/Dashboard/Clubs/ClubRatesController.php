<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Http\Controllers\Controller;
use App\Models\Order\Rate;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ClubRatesController extends Controller
{

    public function index(Request $request,User $club)
    {
        $breadcrumb = [
            'title' =>  (App::getLocale() == "ar") ? $club->name." ".__("Rates") : $club->name_en." ".__("Rates"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Clubs Lists") : __("My Club"),
                    'url'   => route('admin.clubs.index'),
                ],
                [
                    'title' =>  (App::getLocale() == "ar") ? $club->name." ".__("Rates") : $club->name_en." ".__("Rates"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $lists = $club->clubRates()->latest()->paginate();
        return view('admin.pages.clubs.rates.index',compact('breadcrumb', 'lists',"club"));
    }

    public function destroy(Request $request,User $club,Rate $rate) {
        $rate->delete();
        return redirect()->route('admin.clubs.rates.index',$club->id)->with('success', __(":item has been deleted.",['item' => __('Rate')]) );
    }

    public function confirmed(Request $request,User $club,Rate $rate) {
        $rate->update([
            "confirmed" => 1
        ]);
        // ============================= //
        $orderRates = Rate::where(['order_id' => $rate->order_id])->sum("rate") / Rate::where(['order_id' => $rate->order_id])->count();
        $club->update([
            "rates" => $orderRates
        ]);
        // ============================= //
        return redirect()->route('admin.clubs.rates.index',$club->id)->with('success', __(":item has been confirmed.",['item' => __('Rate')]) );
    }
}
