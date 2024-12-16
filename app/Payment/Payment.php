<?php

namespace App\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class Payment {

    protected $fields       = [];
    protected $apiURL       = "https://staging.xpay.app/api/v1/payments/pay/variable-amount";

    private function setFields(Order $order) {
        $this->fields = [
            "billing_data"  => [
                "name"          => "Nammi ".$order->customer->name,
                "email"         => $order->customer->email,
                "phone_number"  => "+2".substr($order->customer->phone,-11)
            ],
            "original_amount"       => $order->total,
            "amount"                => $order->total,
            "currency"              => "EGP",
            "variable_amount_id"    => env("PAYMENT_AMOUNT_ID"),
            "community_id"          => env("PAYMENT_COMMUNITY_ID"),
            "pay_using"             => "card"
        ];
        return $this;
    }

    public function payNow(Order $order) {
        $this->setFields($order);

        $res = Http::withHeaders([
            "x-api-key" => env("PAYMENT_TOKEN"),
            "Content-Type"  => "application/json"
        ])->post($this->apiURL,$this->fields)->json();
        if($res["status"]["code"] != 200) {
            return [
                "status"      => $res["status"]["code"],
                "message"     => $res["status"]["message"],
            ];
        }
        return [
            "status"            => $res["status"]["code"],
            "invoiceId"         => $res["data"]["transaction_id"],
            "invoiceURL"        => $res["data"]["iframe_url"],
            "transaction_uuid"  => $res["data"]["transaction_uuid"],
        ];
    }


    public function checkPayment(Order $order) {
        $res = Http::withHeaders([
            "x-api-key" => env("PAYMENT_TOKEN"),
            "Content-Type"  => "application/json"
        ])->get("https://staging.xpay.app/api/communities/".env("PAYMENT_COMMUNITY_ID")."/transactions/".$order->transaction_id."/")->json();
        if($res["status"]["code"] != 200) {
            return [
                "status"      => false,
            ];
        }
        return [
            "status"   => true,
            "action"   => $res["data"]["status"],
        ];
    }

}
