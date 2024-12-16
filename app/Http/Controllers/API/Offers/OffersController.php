<?php

namespace App\Http\Controllers\API\Offers;

use App\Http\Controllers\Controller;
// Resources
use App\Http\Resources\API\Offer\OfferResource;
// Models
use App\Models\Activity;

class OffersController extends Controller
{
    public function index() {
        $offers = Activity::api()->where("offer","!=",0);
        if(request()->has("category_id")) {
            $offers = $offers->whereHas("categories",function($q) {
                return $q->where("category_id",request("category_id"));
            });
        }
        if(request()->has("price_from") && request("price_from") != '0') {
            if(request()->has("price_to") && request("price_to") != '0') {
                $offers = $offers->whereBetween("price",[request("price_from"),request("price_to")]);
            }
        }
        if(request()->has("rates") && request("rates") != '0') {
            $offers = $offers->where("rates",request("rates"));
        }

        if(request()->has("sort_by_price") && request("sort_by_price") != '0') {
            $offers = $offers->orderBy("price",request("sort_by_price"))->paginate();
        } else {
            $offers = $offers->orderBy("id","desc")->paginate();
        }
        return (new \App\Support\API)
            ->isOk(__("Offers lists"))
            ->setData(OfferResource::collection($offers))
            ->addAttribute("paginate",api_model_set_paginate($offers))
            ->build();
    }
}
