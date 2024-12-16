<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\Register\RegisterPhoneCompletedRequest;
use App\Http\Requests\API\Auth\Register\RegisterPhoneRequest;
// Resources
use App\Http\Resources\API\Profile\ProfileResource;
// Models
use App\Models\User;
use Carbon\Carbon;
// Support
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterPhoneController extends Controller
{
    public function index(RegisterPhoneRequest $request) {
        $user = $this->register($request->validated());
        GenerateAndSendOTP($user);
        return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
            "phone" => __("Oops, please verify you account")
        ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }

    private function register($request) {
        $request["password"]        = Hash::make($request['phone']."-".rand(100,999999));
        $request["email"]           = $request['phone']."@email.app";
        $request["name"]            = $request['phone'];
        $request["phone"]           = $request['phone'];
        $request["account_by"]      = "phone";
        $otp                        = (env('APP_DEBUG')) ? 1234 :  rand(1000,9999);
        $request["otp"]             = $otp;
        $user                       = User::create($request);
        $user->assignRole(User::TYPE_CUSTOMER);
        return $user;
    }
}
