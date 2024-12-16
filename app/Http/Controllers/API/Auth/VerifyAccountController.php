<?php

namespace App\Http\Controllers\API\Auth;

// Controllers
use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\VerifyAccountRequest;
use App\Http\Requests\API\Auth\OTPRequest;
// Models
use App\Models\User;
use Carbon\Carbon;

class VerifyAccountController extends Controller
{
    public function index (VerifyAccountRequest $request) {
        $user = User::where("phone",$request->phone)->first();
        if(is_null($user)) {
            return (new \App\Support\API)->isError(__("Oops, this phone number not found"))->setErrors([
                "phone"    => __("Oops, this phone number not found")
            ])->build();
        }
        if(!in_array(\App\Models\User::TYPE_CUSTOMER,$user->roles()->pluck("name")->toArray()))  {
            return (new \App\Support\API)->isError(__("Oops,you don't have access to cross to this area"))->setErrors([
                "customer"  => __("Oops,you don't have access to cross to this area")
            ])->build();
        }
        if($user->suspend == 1) {
            return (new \App\Support\API)->isError(__("We are sorry but this account has been blocked by the administration"))->setErrors([
                "phone"    => __("We are sorry but this account has been blocked by the administration")
            ])->build();
        }
        if(!is_null($user->phone_verified_at)) {
            return (new \App\Support\API)->isError(__("Oops,This account is verify"))->setErrors([
                "phone"    => __("Oops,This account is verify")
            ])->build();
        }
        GenerateAndSendOTP($user);
        return (new \App\Support\API)->isOk(__("Please check you phone"))->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }
}
