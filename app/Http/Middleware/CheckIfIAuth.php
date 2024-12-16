<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckIfIAuth
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
        if(!is_null($request->header("authorization"))) {
            if ($token = PersonalAccessToken::findToken($request->bearerToken())) {
                if ($user = \App\Models\User::find($token->tokenable_id)) {
                    \Auth::login($user);
                    return $next($request);
                }
            }
        }
        return $next($request);
    }
}
