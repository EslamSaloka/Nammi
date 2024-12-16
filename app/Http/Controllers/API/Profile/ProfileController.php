<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Profile\UpdateProfileRequest;
use App\Http\Requests\API\Profile\UpdatePasswordRequest;
use App\Http\Requests\API\Profile\UpdateProfileAvatarRequest;
use App\Http\Requests\API\Profile\UpdateProfileFireBaseTokenRequest;
// Response
use App\Http\Resources\API\Profile\ProfileResource;
// Support
use App\Support\API;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index() {
        return (new API)->isOk(__("Profile Data."))->setData(new ProfileResource(Auth::user()))->build();
    }

    public function update(UpdateProfileRequest $request) {
        Auth::user()->update($request->validated());
        Auth::user()->categories()->sync(request('categories',[]));
        (new \App\Support\Notification)->setTo(Auth::user()->id)->setTarget(Auth::user()->id)->setTargetType("edit_profile")->setBody(__(":item has been updated.",['item' => __('Profile')]))->build();
        return (new API)->isOk(__("Data changed successfully."))->build();
    }

    public function updateFireBaseToken(UpdateProfileFireBaseTokenRequest $request) {
        Auth::user()->update($request->validated());
        return (new API)->isOk(__("token has been changed successfully."))->build();
    }

    public function updatePassword(UpdatePasswordRequest $request) {
        $user = Auth::user();
        if(!Hash::check($request->current_password, $user->password)) {
            return (new API)->isOk(__("Old password is incorrect."))->setErrors([
                "old_password"  => __("Old password is incorrect.")
            ])->build();
        }
        $user->update([
            "password"  => Hash::make($request->password)
        ]);
        (new \App\Support\Notification)->setTo(Auth::user()->id)->setTarget(Auth::user()->id)->setTargetType("change_password")->setBody(__(":item has been updated.",['item' => __('Profile')]))->build();
        return (new API)->isOk(__("Password changed."))->build();
    }

    public function updateAvatar(UpdateProfileAvatarRequest $request) {
        $data["avatar"] = (new \App\Support\Image)->FileUpload($request->avatar,"Users");
        Auth::user()->update($data);
        return (new API)->isOk(__("Avatar changed."))->build();
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return (new API)->isOk(__("Logout."))->build();
    }

    public function deleteAccount() {
        Auth::user()->delete();
        return (new API)->isOk(__("Your account has been deleted."))->build();
    }
}
