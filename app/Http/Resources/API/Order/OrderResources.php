<?php

namespace App\Http\Resources\API\Order;

use App\Http\Resources\API\City\CityResource;
use App\Http\Resources\API\Club\OrderActivityResource;
use App\Http\Resources\API\Club\OrderBranchResource;
use App\Http\Resources\API\Club\OrderClubResource;
use App\Http\Resources\API\Coupon\CouponResource;
use App\Http\Resources\API\Customer\CustomerResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"                    => $this->id,
            "orderInfo"             => [
                "name"                  => $this->name ?? '',
                "mobile"                => $this->mobile ?? '',
                "notes"                 => $this->notes ?? '',
                "date"                  => $this->date,
                "time"                  => $this->time,
                "created_at"            => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            ],
            "customer"              => is_null($this->customer) ? [] : new CustomerResource($this->customer),
            "club"                  => is_null($this->club) ? [] : new OrderClubResource($this->club),
            "activity"              => is_null($this->activity) ? [] : new OrderActivityResource($this->activity),
            //"branch"              => is_null($this->branch) ? [] : new OrderBranchResource($this->branch),
            "branch"                => is_null($this->branch) ? [] :  OrderBranchResource::collection([$this->branch]),
            "coupon"                => is_null($this->coupon) ? [] : new CouponResource($this->coupon),
            "prices"                => [
                "subPrice"             => $this->sub_price,
                "couponPrice"          => $this->coupon_price,
                "total"                 => $this->total,
            ],
            "orderStatus"           => $this->order_status,
            "payment"               => [
                "paymentType"          => $this->payment_type ?? '',
                "paymentStatus"        => $this->payment_status ?? '',
                "invoiceId"            => $this->invoiceId ?? '',
                "transaction_id"       => $this->transaction_id ?? '',
            ],
        ];
    }
}
