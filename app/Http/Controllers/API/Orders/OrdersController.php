<?php

namespace App\Http\Controllers\API\Orders;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Order\OrderRequest;
use App\Http\Requests\API\Order\RateRequest;
// Resources
use App\Http\Resources\API\Order\OrderResources;
use App\Models\Activity;
use App\Models\Coupon;
// Models
use App\Models\Order;
use App\Models\User;
// Support
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\API;
use Carbon\Carbon;

class OrdersController extends Controller
{

    public function index () {
        if(!request()->has("status")) {
            return (new API)->isError(__("status required"))->setErrors([
                "status"    => __("status required")
            ])->build();
        }
        $orders = Auth::user()->orders();
        if(request("status") == "completed") {
            $orders = $orders->where("order_status","completed");
        } else {
            $orders = $orders->where("order_status","!=","completed");
        }
        $orders = $orders->orderBy("id","desc")->paginate();
        return (new API)->isOk(__("Orders Lists"))->setData(OrderResources::collection($orders))->addAttribute("paginate",api_model_set_paginate($orders))->build();
    }

    public function show (Order $order) {
        if($order->customer_id != Auth::user()->id) {
            return (new API)->isError(__("This order not for you"))->build();
        }
        if($order->activity->order_one_time == 0) {
            if($order->order_status == Order::STATUS_ACCEPTED) {
                if($order->payment_status == "pending") {

                    $payment    = (new \App\Payment\Payment)->payNow($order);
                    if($payment["status"] == false) {
                        $order->delete();
                        return (new API)->isError(__("Error"))->build();
                    }
                    $order->update([
                        "invoiceId"  => $payment['invoiceId']
                    ]);
                    return (new API)->isOk(__("Orders Lists"))->setData(new OrderResources($order))->addAttribute("payment",$payment)->build();
                }
            }
        }
        return (new API)->isOk(__("Orders Lists"))->setData(new OrderResources($order))->build();
    }

    public function store(OrderRequest $request) {
        $request                = $request->validated();
        $request['customer_id'] = Auth::user()->id;
        // $request['country_id']  = request()->header("country");
        // $request['city_id']     = request()->header("city");
        $request['notes']       = request("notes",'');
        // ============================================== //
        // ============================================== //
        // ------------------------ GET PRICES ----------------------//
        $club       = User::find($request['club_id']);
        if(is_null($club) || !in_array(User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new API)->isError(__("This club not found"))->setErrors([
                "club_id" => __("This club not found")
            ])->build();
        }

        // =========================================== //
        // Check If This Branch For This Club
        $clubBranches = $club->clubBranches()->pluck("id")->toArray();
        if(!in_array($request['branch_id'],$clubBranches)) {
            return (new API)->isError(__("This club not supported for this branch"))->setErrors([
                "branch_id" => __("This club not supported for this branch")
            ])->build();
        }

        // ------- //
        $activate   = Activity::api()->where(["id"=>$request['activity_id']])->first();
        if(is_null($activate) || !in_array($activate->id,$club->clubActivities()->pluck("id")->toArray())) {
            return (new API)->isError(__("This activate not found"))->setErrors([
                "activity_id" => __("This activate not found")
            ])->build();
        }

        if($activate->branch_id != $request['branch_id']) {
            return (new API)->isError(__("This activate not supported for this branch"))->setErrors([
                "activity_id" => __("This activate not supported for this branch")
            ])->build();
        }

        if(!in_array($request["payment_type"],$activate->payment_types)) {
            return (new API)->isError(__("This activate payment not supported"))->setErrors([
                "activate" => __("This activate payment not supported")
            ])->build();
        }

        // ------- //
        $price = ($activate->offer == 0) ? $activate->price : $activate->offer;
        $request['sub_price']       = $price;

        $couponPrice = 0;
        if(request()->has("coupon")) {
            $coupon = Coupon::where("name",request("coupon"))->first();
            if(is_null($coupon)) {
                return (new API)->isError(__("This coupon not found"))->setErrors([
                    "coupon" => __("This coupon not found")
                ])->build();
            }
            if(Carbon::parse($coupon->expire) < Carbon::now()) {
                return (new API)->isError(__("This coupon is expire"))->setErrors([
                    "coupon" => __("This coupon is expire")
                ])->build();
            }
            if($coupon->type == "fixed") {
                $couponPrice = $coupon->value;
            } else {
                $couponPrice = ($price * $coupon->value) / 100;
            }
        }
        $request['coupon_price']    = $couponPrice;
        $request['total']           = $price - $couponPrice;
        // ============================================== //
        if($activate->order_one_time == 1) {
            if($request["payment_type"] != "visa") {
                $request['order_status'] = order::STATUS_CONFIRMED;
            }
        }
        $request['date']            = Carbon::parse($request['date'])->format('d/m/Y');
        $request['time']            = Carbon::parse($request['time'])->format('H:i:s');
        $order = Order::create($request);
        $order->histories()->create([
            "order_status"    => Order::STATUS_PENDING
        ]);
        if($activate->order_one_time == 0) {
            return (new API)->isOk(__("Orders ".Order::STATUS_PENDING))->setData(new OrderResources(Order::find($order->id)))->build();
        }
        if($activate->order_one_time == 1) {
            if($request["payment_type"] != "visa") {
                $order->histories()->create([
                    "order_status"    => Order::STATUS_CONFIRMED
                ]);
            }
        }
        if($request['payment_type'] == "visa") {
            if($activate->order_one_time == 1) {
                $payment    = (new \App\Payment\Payment)->payNow($order);
                if($payment["status"] != 200) {
                    $order->delete();
                    return (new API)->isError(__("Error"))->build();
                }
                $order->update([
                    "invoiceId"         => $payment['invoiceId'],
                    "transaction_id"    => $payment['transaction_uuid'],
                ]);
                return (new API)->isOk(__("Payment Getaway"))->setData(new OrderResources(Order::find($order->id)))->addAttribute("payment",$payment)->build();
            }
        }
        return (new API)->isOk(__("Orders Created"))->setData(new OrderResources(Order::find($order->id)))->build();
    }

    public function callBack () {
        $order = Order::where([
            "transaction_id"    => request("transaction_id")
        ])->first();
        if(is_null($order)) {
            return (new API)->isError(__("This order not found"))->setErrors([
                "order" => __("This order not found")
            ])->build();
        }
        if(request("transaction_status") == "SUCCESSFUL") {
            $order->update([
                "order_status"      => Order::STATUS_CONFIRMED,
                "payment_status"    => "paid"
            ]);
            $order->histories()->create([
                "order_status"    => Order::STATUS_CONFIRMED
            ]);
            (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_CONFIRMED)->setToken($order->customer->fire_base_token)->build();
            return (new API)->isOk(__("This payment order is ".Order::STATUS_CONFIRMED))->setData([
                "transaction_uuid"      => request("transaction_id"),
                "transaction_status"    => request("transaction_status")
            ])->build();

        } elseif (request("transaction_status") == "FAILED") {
            $order->update([
                "order_status"      => Order::STATUS_REJECTED,
                "payment_status"    => "unpaid"
            ]);
            $order->histories()->create([
                "order_status"    => Order::STATUS_REJECTED
            ]);
            (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_REJECTED)->setToken($order->customer->fire_base_token)->build();
            return (new API)->isError(__("This payment order is ".Order::STATUS_REJECTED))->setData([
                "transaction_uuid"      => request("transaction_id"),
                "transaction_status"    => request("transaction_status")
            ])->build();

        } elseif (request("transaction_status") == "EXPIRED") {
            $order->update([
                "order_status"      => Order::STATUS_REJECTED,
                "payment_status"    => "unpaid"
            ]);
            $order->histories()->create([
                "order_status"    => Order::STATUS_REJECTED
            ]);
            (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_REJECTED)->setToken($order->customer->fire_base_token)->build();
            return (new API)->isError(__("This payment order is ".Order::STATUS_REJECTED))->setData([
                "transaction_uuid"      => request("transaction_id"),
                "transaction_status"    => request("transaction_status")
            ])->build();

        }

        return (new API)->isOk(__("This payment order is ".Order::STATUS_PENDING))->setData([
            "transaction_uuid"      => request("transaction_id"),
            "transaction_status"    => request("transaction_status")
        ])->build();
    }

    public function rate(RateRequest $request,Order $order) {
        if($order->customer_id != Auth::user()->id) {
            return (new API)->isError(__("This order not for you"))->build();
        }
        if($order->order_status != Order::STATUS_COMPLETED) {
            return (new API)->isError(__("This order not completed"))->build();
        }

        $orderRATE = \App\Models\Order\Rate::where([
            'customer_id'   => $order->customer_id,
            'club_id'       => $order->club_id,
            'order_id'      => $order->id,
        ])->first();
        if(!is_null($orderRATE)) {
            return (new API)->isError(__("This order rating before"))->build();
        }

        \App\Models\Order\Rate::create([
            'customer_id'   => $order->customer_id,
            'club_id'       => $order->club_id,
            'order_id'      => $order->id,
            "rate"          => $request->club_rate,
            "notes"         => $request->club_rate_message,
        ]);
        // ================================================ //
        \App\Models\Activity\Rate::create([
            'customer_id'   => $order->customer_id,
            'activity_id'   => $order->activity_id,
            "rate"          => $request->activity_rate,
            "notes"         => $request->activity_rate_message,
        ]);

        return (new API)->isOk(__("Thanks for this rating"))->build();
    }

    public function destroy(Order $order) {
        if($order->customer_id != Auth::user()->id) {
            return (new API)->isError(__("This order not for you"))->build();
        }
        // =============================== //
        if($order->order_status == Order::STATUS_COMPLETED) {
            return (new API)->isError(__("This order is completed"))->build();
        }
        // =============================== //
        $order->update([
            "order_status"  =>  Order::STATUS_REJECTED
        ]);
        $order->histories()->create([
            "order_status"    => Order::STATUS_REJECTED
        ]);
        return (new API)->isOk(__("Your booking has been deleted"))->build();
    }

    public function acceptChangeTime(Order $order) {
        if($order->customer_id != Auth::user()->id) {
            return (new API)->isError(__("This order not for you"))->build();
        }
        // =============================== //
        if($order->order_status == Order::STATUS_COMPLETED) {
            return (new API)->isError(__("This order is completed"))->build();
        }
        if($order->order_status != Order::STATUS_TIME_CHANGE) {
            return (new API)->isError(__("This order not supported this end point"))->build();
        }
        if($order->activity->order_one_time != 0) {
            return (new API)->isError(__("This order not supported this end point"))->build();
        }
        // =============================== //
        $order->update([
            "order_status"  =>  Order::STATUS_CONFIRMED
        ]);
        $order->histories()->create([
            "order_status"    => Order::STATUS_CONFIRMED
        ]);
        return (new API)->isOk(__("Your booking has been confirmed"))->build();
    }
}
