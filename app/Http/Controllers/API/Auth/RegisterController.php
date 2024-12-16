<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\Social\RegisterSocialRequest;
use App\Models\User;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request) {
        $otp                     = (env('SMS_LIVE') == false) ? 1234 :  rand(1000,9999);
        $request                 = $request->validated();
        $request["otp"]          = $otp;
        $request['completed_at'] = Carbon::now();
        $user = User::create($request);
        $user->categories()->sync(request('categories',[]));
        $user->assignRole(User::TYPE_CUSTOMER);
        // ========================================= //
        if(env('SMS_LIVE')) {
            (new \App\Support\SMS)->setPhone($request['phone'])->setMessage($otp);
        }
        // ============================================= //
        return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
            "account" => __("Oops, please verify you account")
        ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }
}
