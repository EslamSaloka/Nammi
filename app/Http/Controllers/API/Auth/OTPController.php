<?php

namespace App\Http\Controllers\API\Auth;

// Controllers
use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\OTPRequest;
use App\Http\Resources\API\Profile\ProfileResource;
// Models
use App\Models\User;
// Support
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function index(OTPRequest $request) {
        $user = Auth::user();
        if($user->suspend == 1) {
            return (new \App\Support\API)->isError(__("We are sorry but this account has been blocked by the administration"))->setErrors([
                "email"    => __("We are sorry but this account has been blocked by the administration")
            ])->build();
        }
        $i = ((int)$user->otp === (int)$request->otp) ? true : false;
        if($i == false) {
            return (new \App\Support\API)->isError(__("Oops, This code not true"))->setErrors([
                "otp"    => __("Oops, This code not true")
            ])->build();
        }
        if($request->by == "forget") {
            $user->update([
                "otp"   => (env('APP_DEBUG')) ? 1234 :  rand(1000,9999)
            ]);
            return (new \App\Support\API)->isOk(__("Thanks, now you can reset your password"))->build();
        }
        if(!is_null($user->phone_verified_at)) {
            return (new \App\Support\API)->isError(__("This account was verified by"))->setErrors([
                "account"    => __("This account was verified by")
            ])->build();
        }
        $user->update([
            "otp"                   => (env('APP_DEBUG')) ? 1234 :  rand(1000,9999),
            "phone_verified_at"     => Carbon::now()
        ]);
        if($user->account_by == "phone" && is_null($user->completed_at)) {
            return (new \App\Support\API)->isError(__("Oops, please complete your account"))->setErrors([
                "account" => __("Oops, please complete your account")
            ])->build();
        }
        return (new \App\Support\API)->isOk(__("Congratulation your account is verify"))->setData(new ProfileResource($user))->build();
    }
}
