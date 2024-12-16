<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\Social\RegisterSocialCompletedRequest;
use App\Http\Requests\API\Auth\Social\RegisterSocialRequest;
// Resources
use App\Http\Resources\API\Profile\ProfileResource;
// Models
use App\Models\User;
// Support
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginSocialController extends Controller
{
    public function index(RegisterSocialRequest $request) {
        $socialAccount = User::where([
            "account_by"   => $request->account_by,
            "social_id"    => $request->social_id,
        ])->first();
        if(is_null($socialAccount)) {
            $user = $this->register($request->validated());
        } else {
            $user = $socialAccount;
        }
        if($user->phone == $request->social_id) {
            return (new \App\Support\API)->isError(__("Oops, please complete your account"))->setErrors([
                "account" => __("Oops, please complete your account")
            ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
        }
        if(!is_null($user->phone_verified_at)) {
            return (new \App\Support\API)->isOk(__("User Information"))->setData(new ProfileResource($user))->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
        }
        GenerateAndSendOTP($user);
        return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
            "phone" => __("Oops, please verify you account")
        ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }

    private function register($request) {
        $request["password"] = Hash::make($request['account_by']."-".$request['social_id']."-".$request['name']."-".rand(100,999999));
        $request["email"]    = $request['social_id']."@email.app";
        $request["phone"]    = $request['social_id'];
        $user                = User::create($request);
        $user->assignRole(User::TYPE_CUSTOMER);
        return $user;
    }

    public function completeAccount(RegisterSocialCompletedRequest $request) {
        $user = Auth::user();
        if($user->phone != $user->social_id && $user->account_by != "normal") {
            return (new \App\Support\API)->isError(__("Oops,you don't have access to cross to this area"))->setErrors([
                "account" => __("Oops,you don't have access to cross to this area")
            ])->build();
        }
        $user->update($request->validated());
        // ===================================== //
        GenerateAndSendOTP($user);
        return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
            "phone" => __("Oops, please verify you account")
        ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }
}
