<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function index(ResetPasswordRequest $request) {
        Auth::user()->update([
            "password"  => Hash::make($request->password)
        ]);
        return (new \App\Support\API)->isOk(__("Password changed now you can login."))->build();
    }
}
