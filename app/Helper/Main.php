<?php

use App\Models\Order\Due;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('_fixDirSeparator')) {
    function _fixDirSeparator($path) {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    }
}

if(!function_exists('action_table_delete')) {
    function action_table_delete($route,$index = 0) {
        return '<form action="' . $route . '" method="post" id="form_'.$index.'">
        <input name="_method" type="hidden" value="delete">
        <input type="hidden" name="_token" id="csrf-token" value="' . Session::token() . '" />
        <a class="btn btn-outline-danger btn-sm row_deleted" data-bs-toggle="modal" data-id="'.$index.'" data-bs-target="#staticBackdrop">
            <i class="bx bx-trash"></i>
        </a>
        </form>';
    }
}

if (!function_exists('getSettings')) {
    function getSettings($var = null, $default = null,$trans = false)
    {
        $settings = \App\Models\Setting::get()->toArray();
        $data = array_column($settings, 'value', 'key');
        if(is_null($var)) {
            return $data;
        }
        return isset($data[$var]) ? $data[$var] : $default;
    }
}

if (!function_exists('getUnSeenAdminNotificationsCount')) {
    function getUnSeenAdminNotificationsCount()
    {
        return \App\Models\Notification::where("user_id",\Auth::user()->id)->where("seen",0)->count();
    }
}

if (!function_exists('getUnSeenAdminNotificationsTake')) {
    function getUnSeenAdminNotificationsTake()
    {
        return \App\Models\Notification::where("user_id",\Auth::user()->id)->where("seen",0)->latest()->get();
    }
}




if (!function_exists('getAboutPage')) {
    function getAboutPage() {
        // return Cache::rememberForever("global_about_page", function () {
        // });
        return \App\Models\Page::where("slug","about")->first()->content ?? '';
    }
}

if (!function_exists('getTermsPage')) {
    function getTermsPage() {
        // return Cache::rememberForever("global_terms_page", function () {
        // });
        return \App\Models\Page::where("slug","terms_of_services")->first()->content ?? '';
    }
}

if (!function_exists('getPolicyPage')) {
    function getPolicyPage() {
        // return Cache::rememberForever("global_privacy_page", function () {
        // });
        return \App\Models\Page::where("slug","privacy_policy")->first()->content ?? '';
    }
}

if (!function_exists('getFAQPage')) {
    function getFAQPage() {
        // return Cache::rememberForever("global_FAQ_page", function () {
        // });
        return \App\Models\FAQ::all();
    }
}

if (!function_exists('getTimeHobby')) {
    function getTimeHobby() {
        // return Cache::rememberForever("time_of_hobby", function () {
        // });
        return \App\Models\Time::all();
    }
}


if (!function_exists('getParentCategories')) {
    function getParentCategories($fullData = true) {
        if($fullData === false) {
            if(request()->is("api/*")) {
                if(\Auth::check()) {
                    return \Auth::user()->categories()->where("parent_id",0)->get();
                }
            }
        }
        return \App\Models\Category::where("parent_id",0)->get();
    }
}

if (!function_exists('api_model_set_paginate')) {

    function api_model_set_paginate($model)
    {
        return [
            'total'         => $model->total(),
            'lastPage'      => $model->lastPage(),
            'perPage'       => $model->perPage(),
            'currentPage'   => $model->currentPage(),
        ];
    }
}


if (!function_exists('nearClubsLocation')) {
    function nearClubsLocation($lat, $lng)
    {
        $distance = 20;
        $query = <<<EOF
        SELECT `club_id` FROM (
          SELECT *,
              (
                  (
                      (
                          acos(
                              sin(( $lat * pi() / 180))
                              *
                              sin(( `lat` * pi() / 180)) + cos(( $lat * pi() /180 ))
                              *
                              cos(( `lat` * pi() / 180)) * cos((( $lng - `lng`) * pi()/180)))
                      ) * 180/pi()
                  ) * 60 * 1.1515 * 1.609344
              )
          as distance FROM `club_branches`
      ) club_branches
      WHERE distance <= $distance
      LIMIT 15
EOF;

        $clubs = \DB::select($query);
        $array = [];
        foreach($clubs as $club) {
            $array[] = $club->club_id;
        }
        return $array;
    }
}

if (!function_exists('nearClubBranch')) {
    function nearClubBranch($lat, $lng,$club_id)
    {
        $distance = 20;
        $query = <<<EOF
        SELECT `id` FROM (
          SELECT *,
              (
                  (
                      (
                          acos(
                              sin(( $lat * pi() / 180))
                              *
                              sin(( `lat` * pi() / 180)) + cos(( $lat * pi() /180 ))
                              *
                              cos(( `lat` * pi() / 180)) * cos((( $lng - `lng`) * pi()/180)))
                      ) * 180/pi()
                  ) * 60 * 1.1515 * 1.609344
              )
          as distance FROM `club_branches`
      ) club_branches
      WHERE distance <= $distance AND club_id = $club_id
      LIMIT 15
EOF;

        $clubs = \DB::select($query);
        $array = [];
        foreach($clubs as $club) {
            $array[] = $club->id;
        }
        return $array;
    }
}


if (!function_exists('GenerateAndSendOTP')) {
    function GenerateAndSendOTP(\App\Models\User $user) {
        $otp = (env('SMS_LIVE') == false) ? 1234 :  rand(1000,9999);
        $user->update([
            'otp'   => $otp
        ]);
        if(env('SMS_LIVE')) {
            (new \App\Support\SMS)->setPhone($user->phone)->setMessage($otp);
        }
    }
}

if (!function_exists('GenerateAndSendOTPForOrder')) {
    function GenerateAndSendOTPForOrder(\App\Models\Order $order) {
        $otp = (env('SMS_LIVE') == false) ? 1234 :  rand(1000,9999);
        $order->update([
            'otp'   => $otp
        ]);
        if(env('SMS_LIVE')) {
            (new \App\Support\SMS)->setPhone($order->customer->phone)->setMessage($otp);
        }
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        if(in_array(\App\Models\User::TYPE_CLUB,\Auth::user()->roles()->pluck("name")->toArray())) {
            return false;
        }
        if(in_array(\App\Models\User::TYPE_BRANCH,\Auth::user()->roles()->pluck("name")->toArray())) {
            return false;
        }
        return true;
    }
}

if (!function_exists('isBranchMaster')) {
    function isBranchMaster() {
        if(in_array(\App\Models\User::TYPE_BRANCH,\Auth::user()->roles()->pluck("name")->toArray())) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getDuesPrice')) {
    function getDuesPrice(\App\Models\User $user) {
        return \App\Models\Order\Due::select("price")->where("confirmed",0)->where("club_id",$user->id)->where('order_by','cod')->sum("price") ?? 0;
    }
}

if (!function_exists('getClubRevenuePrice')) {
    function getClubRevenuePrice(\App\Models\User $user) {
        $app_dues = \App\Models\Order\Due::select("price")->where("confirmed",0)->where("club_id",$user->id)->where('order_by','visa')->sum("price") ?? 0;
        $order_total = \App\Models\Order\Due::select("order_total_price")->where("confirmed",0)->where("club_id",$user->id)->where('order_by','visa')->sum("order_total_price") ?? 0;
        $club_revenue = $order_total - $app_dues;
        return $club_revenue ?? 0;
    }
}

if (!function_exists('getDues')) {
    function getDues(\App\Models\User $user) {
        $total          = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where('order_by','cod');
        $confirmed      = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where("confirmed",1)->where('order_by','cod');
        $un_confirmed   = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where("confirmed",0)->where('order_by','cod');
        if (request()->has('from_date') && request('from_date') != '') {
            $total          = $total->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
            $confirmed      = $confirmed->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
        }
        if (request()->has('to_date') && request('to_date') != '') {
            $total          = $total->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
            $confirmed      = $confirmed->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
        }
        if (request()->has('payment_type') && request('payment_type') != '-1') {
            $total          = $total->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $confirmed      = $confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $un_confirmed   = $un_confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
        }
        return [
            "total"         => (is_null($total)) ? 0 : $total->sum("price"),
            "confirmed"     => (is_null($confirmed)) ? 0 : $confirmed->sum("price"),
            "un_confirmed"  => (is_null($un_confirmed)) ? 0 : $un_confirmed->sum("price"),
        ];
    }
}

if (!function_exists('getClubRevenue')) {
    function getClubRevenue(\App\Models\User $user) {

        $total_dues               = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where('order_by','visa');
        $total_order_cost         = \App\Models\Order\Due::select("order_total_price")->where("club_id",$user->id)->where('order_by','visa');
        $total                    = $total_order_cost->sum("order_total_price") - $total_dues->sum("price");

        $confirmed_dues           = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where("confirmed",1)->where('order_by','visa');
        $confirmed_order_cost     = \App\Models\Order\Due::select("order_total_price")->where("club_id",$user->id)->where("confirmed",1)->where('order_by','visa');
        $confirmed                = $confirmed_order_cost->sum("order_total_price") - $confirmed_dues->sum("price");

        $un_confirmed_dues        = \App\Models\Order\Due::select("price")->where("club_id",$user->id)->where("confirmed",0)->where('order_by','visa');
        $un_confirmed_order_cost  = \App\Models\Order\Due::select("order_total_price")->where("club_id",$user->id)->where("confirmed",0)->where('order_by','visa');
        $un_confirmed             = $un_confirmed_order_cost->sum("order_total_price") - $un_confirmed_dues->sum("price");

        if (request()->has('from_date') && request('from_date') != '') {
            $total          = $total->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
            $confirmed      = $confirmed->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '>=', \Carbon\Carbon::parse(request('from_date')));
        }
        if (request()->has('to_date') && request('to_date') != '') {
            $total          = $total->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
            $confirmed      = $confirmed->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '<=', \Carbon\Carbon::parse(request('to_date')));
        }

        return [
            "total"         => (is_null($total)) ? 0 : $total,
            "confirmed"     => (is_null($confirmed)) ? 0 : $confirmed,
            "un_confirmed"  => (is_null($un_confirmed)) ? 0 : $un_confirmed,
        ];
    }
}

if (!function_exists('isFav')) {
    function isFav($user,$club) {
        if(is_null($user) || is_null($club)) {
            return false;
        }
        if(in_array($club->id,$user->fav()->pluck('club_id')->toArray())) {
            return true;
        }
        return false;
    }
}
