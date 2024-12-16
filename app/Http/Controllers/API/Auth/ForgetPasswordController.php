<?php

namespace App\Http\Controllers\API\Auth;

// Controllers
use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\ForgetPasswordRequest;
// Models
use App\Models\User;

class ForgetPasswordController extends Controller
{
    public function index(ForgetPasswordRequest $request) {
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
                "email"    => __("We are sorry but this account has been blocked by the administration")
            ])->build();
        }
        GenerateAndSendOTP($user);
        return (new \App\Support\API)->isOk(__("Please check you phone"))->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }
}
