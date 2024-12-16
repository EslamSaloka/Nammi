<?php

namespace App\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Payment2 {

    protected $token        = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
    protected $apiURL       = "https://apitest.myfatoorah.com";
    // ========================================== //
    protected $fields       = [];

    public function __construct() {
        if(env("MYFATOORA_LIVE")) {
            $this->token    = env("MYFATOORA_TOKEN");
            $this->apiURL   = "https://api-sa.myfatoorah.com/";
        }
    }


    public function getFields($data) {
        $this->fields = [
            'CustomerName'       => $data["username"],
            'NotificationOption' => 'All',
            'MobileCountryCode'  => '966',
            'CustomerMobile'     => substr($data['phone'], 3),
            'CustomerEmail'      => $data['email'],
            'InvoiceValue'       => $data["total"],
            'DisplayCurrencyIso' => 'SAR',
            'CallBackUrl'        => url("api/callback"),
            'ErrorUrl'           => url("api/callback"),
            'Language'           => 'en',
            'CustomerReference'  => $data["id"],
        ];
        return $this;
    }

    public function payNow(Order $order) {
        $data = [
            "id"            => $order->id,
            "username"      => $order->name,
            "email"         => $order->customer->email,
            "phone"         => $order->mobile,
            "total"         => $order->total,
        ];
        $this->getFields($data);

        $respons = Http::withHeaders([
            "Authorization" => "Bearer ".$this->token,
            "Content-Type"  => "application/json"
        ])->post($this->apiURL."/v2/SendPayment",$this->fields)->json();
        if($respons["IsSuccess"] == false) {
            return [
                "status"      => $respons["IsSuccess"],
                "message"     => $respons["Message"],
            ];
        }
        return [
            "status"      => $respons["IsSuccess"],
            "invoiceId"   => $respons["Data"]["InvoiceId"],
            "InvoiceURL"  => $respons["Data"]["InvoiceURL"],
        ];
    }

    // ================================================ //
    // ================================================ //
    // ================================================ //
    // ================================================ //
    public function callBack($paymentId) {
        $data = Http::withHeaders([
            "Authorization" => "Bearer ".$this->token,
            "Content-Type"  => "application/json"
        ])->post($this->apiURL."/v2/getPaymentStatus",[
            'KeyType' => 'PaymentId',
            'Key' => $paymentId,
        ])->json();
        return $data;
    }

}
