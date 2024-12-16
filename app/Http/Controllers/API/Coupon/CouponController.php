<?php

namespace App\Http\Controllers\API\Coupon;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Coupon\CouponRequest;
// Resources
use App\Http\Resources\API\Coupon\CouponResource;
// Models
use App\Models\Coupon;
// Support
use Carbon\Carbon;

class CouponController extends Controller
{

    public function index(CouponRequest $request) {
        $coupon =  Coupon::where($request->validated())->first();
        if(is_null($coupon)) {
            return (new \App\Support\API)->isError(__("This coupon not found"))->setErrors([
                "coupon" => __("This coupon not found")
            ])->build();
        }
        if(Carbon::parse($coupon->expire) < Carbon::now()) {
            return (new \App\Support\API)->isError(__("This coupon is expire"))->setErrors([
                "coupon" => __("This coupon is expire")
            ])->build();
        }
        return (new \App\Support\API)->isOk(__("coupon data"))->setData(new CouponResource($coupon))->build();
    }
}
