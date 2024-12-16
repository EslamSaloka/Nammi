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
use Carbon\Carbon;
// Support
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CompletedAccountController extends Controller
{
    public function index(RegisterSocialCompletedRequest $request) {
        $user = Auth::user();
        if($user->account_by == "normal") {
            return (new \App\Support\API)->isError(__("Oops,you don't have access to cross to this area"))->setErrors([
                "account" => __("Oops,you don't have access to cross to this area")
            ])->build();
        }
        if($user->account_by == "phone" && !is_null($user->completed_at)) {
            return (new \App\Support\API)->isError(__("Oops,you don't have access to cross to this area"))->setErrors([
                "account" => __("Oops,you don't have access to cross to this area")
            ])->build();
        }
        $request                    = $request->validated();
        $request['completed_at']    = Carbon::now();
        $request['password']        = Hash::make($request['password']);
        $user->update($request);
        // ===================================== //
        if(is_null($user->phone_verified_at)) {
            GenerateAndSendOTP($user);
            return (new \App\Support\API)->isOk(__("Oops, please verify you account"))->setErrors([
                "phone" => __("Oops, please verify you account")
            ])->build();
        }
        return (new \App\Support\API)->isOk(__("User Information"))->setData(new ProfileResource($user))->build();
    }
}
