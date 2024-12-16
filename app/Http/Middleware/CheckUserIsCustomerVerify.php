<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsCustomerVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            if(is_null(Auth::user()->phone_verified_at)) {
                return (new \App\Support\API)->isError(__("Oops, please verify you account"))->setErrors([
                    "account" => __("Oops, please verify you account")
                ])->addAttribute("api_token",Auth::user()->createToken("API TOKEN")->plainTextToken)->build();
            }
            if(is_null(Auth::user()->completed_at)) {
                return (new \App\Support\API)->isError(__("Oops, please completed you account"))->setErrors([
                    "account" => __("Oops, please completed you account")
                ])->addAttribute("api_token",Auth::user()->createToken("API TOKEN")->plainTextToken)->build();
            }
        }
        return $next($request);
    }
}
