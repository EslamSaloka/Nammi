<?php

namespace App\Http\Controllers\API\Auth;

// Controllers
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReSendController extends Controller
{
    public function index() {
        GenerateAndSendOTP(Auth::user());
        return (new \App\Support\API)->isOk(__("OTP SMS sent successfully"))->build();
    }
}
