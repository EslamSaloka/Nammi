<?php

namespace App\Http\Controllers\API\Clubs;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Club\ClubActivityRatesResource;
// Resources
use App\Http\Resources\API\Club\ClubActivityResource;
use App\Http\Resources\API\Club\ClubRateResource;
use App\Http\Resources\API\Club\ClubResource;
use App\Models\Activity;
// Models
use App\Models\User as Club;
use Illuminate\Support\Facades\App;

class ClubsController extends Controller
{
    public function index() {
        $clubs = Club::whereHas('roles', function($q) {
            return $q->where('name', '=', \App\Models\User::TYPE_CLUB);
        });

        if(request()->has("name") && request("name") != '') {
            if(App::getLocale() == "ar") {
                $clubs = $clubs->where("name","LIKE","%".request("name")."%");
            } else {
                $clubs = $clubs->where("name_en","LIKE","%".request("name")."%");
            }
        }

        if(request()->has("category") && request("category") != '0') {
            $clubs = $clubs->whereHas("categories",function($q){
                return $q->where("category_id",request('category'));
            });
        }

        if(request()->has("country") && request("country") != '0') {
            $clubs = $clubs->whereHas("clubBranches",function($q){
                return $q->where("country_id",request("country"));
            });
        } else {
            $clubs = $clubs->whereHas("clubBranches",function($q){
                return $q->where("country_id",request()->header("country"));
            });
        }

        if(request()->has("city") && request("city") != '0') {
            $clubs = $clubs->whereHas("clubBranches",function($q){
                return $q->where("city_id",request("city"));
            });
        } else {
            $clubs = $clubs->whereHas("clubBranches",function($q){
                return $q->where("city_id",request()->header("city"));
            });
        }

        if(request()->has("rates") && request("rates") != '0') {
            $clubs = $clubs->where("rates",request("rates"));
        }


        $clubs  = $clubs->orderBy("id","desc")->paginate();
        return (new \App\Support\API)->isOk(__("Clubs Lists"))
            ->setData(ClubResource::collection($clubs))
            ->addAttribute("paginate",api_model_set_paginate($clubs))
            ->build();
    }

    public function show(Club $club) {
        if(!in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This id not club"))->build();
        }
        // Check Area Branch
        $check = $club->whereHas("clubBranches",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        })->first();
        if(is_null($check)) {
            return (new \App\Support\API)->isError(__("This club not supported for this area"))->build();
        }
        // =================================== //
        return (new \App\Support\API)->isOk(__("Club Information"))->setData(new ClubResource($club))->build();
    }

    public function rates(Club $club) {
        if(!in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This id not club"))->build();
        }
        // Check Area Branch
        $check = $club->whereHas("clubBranches",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        })->first();
        if(is_null($check)) {
            return (new \App\Support\API)->isError(__("This club not supported for this area"))->build();
        }
        // =================================== //
        $rates = $club->clubRates()->where("confirmed",1)->orderBy("id","desc")->paginate();
        return (new \App\Support\API)->isOk(__("Club Rates Lists"))->setData(ClubRateResource::collection($rates))->addAttribute("paginate",api_model_set_paginate($rates))->build();
    }

    public function activities(Club $club) {
        if(!in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This id not club"))->build();
        }
        // Check Area Branch
        $check = $club->whereHas("clubBranches",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        })->first();
        if(is_null($check)) {
            return (new \App\Support\API)->isError(__("This club not supported for this area"))->build();
        }
        // =================================== //
        $activities = $club->clubActivities()->api();
        if(request()->has("category_id")) {
            $activities = $activities->whereHas("categories",function($q) {
                return $q->where("category_id",request("category_id"));
            });
        }
        if(request()->has("is_offer")) {
            if(request("is_offer") == 1) {
                $activities = $activities->where("offer","!=",0);
            }
        }

        if(request()->has("disabilities")) {
            if(request("disabilities") == 1) {
                $activities = $activities->where("disabilities",1);
            }else{
                $activities = $activities->where("disabilities",0);
            }
        }


        if(request()->has("price_from") && request("price_from") != '0') {
            if(request()->has("price_to") && request("price_to") != '0') {
                $activities = $activities->whereBetween("price",[request("price_from"),request("price_to")]);
            }
        }

        if(request()->has("rates") && request("rates") != '0') {
            $activities = $activities->where("rates",request("rates"));
        }

        if(request()->has("sort_by_price") && request("sort_by_price") != '0') {
            $activities = $activities->orderBy("price",request("sort_by_price"))->paginate();
        } else {
            $activities = $activities->orderBy("id","desc")->paginate();
        }

        return (new \App\Support\API)->isOk(__("Club Activities Lists"))->setData(ClubActivityResource::collection($activities))->addAttribute("paginate",api_model_set_paginate($activities))->build();
    }

    public function showActivity(Club $club,Activity $activity) {
        if(!in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This id not club"))->build();
        }
        // Check Area Branch
        $check = $club->whereHas("clubBranches",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        })->first();
        if(is_null($check)) {
            return (new \App\Support\API)->isError(__("This club not supported for this area"))->build();
        }
        // =================================== //
        if($club->id != $activity->club_id) {
            return (new \App\Support\API)->isError(__("This club not supported for this activity"))->build();
        }
        return (new \App\Support\API)->isOk(__("Show activity"))->setData(new ClubActivityResource($activity))->build();
    }

    public function showActivityRates(Club $club,Activity $activity) {
        if(!in_array(\App\Models\User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This id not club"))->build();
        }
        // Check Area Branch
        $check = $club->whereHas("clubBranches",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        })->first();
        if(is_null($check)) {
            return (new \App\Support\API)->isError(__("This club not supported for this area"))->build();
        }
        // =================================== //
        if($club->id != $activity->club_id) {
            return (new \App\Support\API)->isError(__("This club not supported for this activity"))->build();
        }
        $rates = $activity->getRates()->where('confirmed',1)->orderBy("id","desc")->paginate();
        return (new \App\Support\API)->isOk(__("Show activity rates"))->setData(ClubActivityRatesResource::collection($rates))->addAttribute("paginate",api_model_set_paginate($rates))->build();
    }
}
