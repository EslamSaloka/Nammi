<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsCustomer
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
            if(!in_array(\App\Models\User::TYPE_CUSTOMER,Auth::user()->roles()->pluck("name")->toArray()))  {
                return (new \App\Support\API)->isError(__("Oops,you don't have access to cross to this area"))->setErrors([
                    "customer"  => __("Oops,you don't have access to cross to this area")
                ])->build();
            }
            Auth::user()->update([
                "last_action_at"    => \Carbon\Carbon::now()
            ]);
        }
        return $next($request);
    }
}
