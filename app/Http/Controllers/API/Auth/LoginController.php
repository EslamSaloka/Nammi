<?php

namespace App\Http\Controllers\API\Auth;

// Controllers
use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Auth\LoginRequest;
// Response
use App\Http\Resources\API\Profile\ProfileResource;
// Models
use App\Models\User;
// Support
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(LoginRequest $request) {
        $authOnce = false;
        // =============================== //
        $data["password"] = $request->password;
        if(is_numeric($request->object)) {
            $data["phone"] = $request->object;
        } else {
            $data["email"] = $request->object;
        }
        // =============================== //
        $authOnce = Auth::once($data);
        if(!$authOnce) {
            return (new \App\Support\API)->isError(__("We are sorry but we do not have this data"))->setErrors([
                "object"    => __("We are sorry but we do not have this data")
            ])->build();
        }
        $user = User::find(Auth::getUser()->id);
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
        if(!is_null($user->phone_verified_at)) {
            return (new \App\Support\API)->isOk(__("User Information"))->setData(new ProfileResource($user))->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
        }
        // =================================== //
        GenerateAndSendOTP($user);
        // ============================================= //
        return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
            "account" => __("Oops, please verify you account")
        ])->addAttribute("api_token",$user->createToken("API TOKEN")->plainTextToken)->build();
    }
}
