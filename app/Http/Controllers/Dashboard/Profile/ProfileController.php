<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdatePasswordRequest;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateStoreDataRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            'title' =>  __("My Profile"),
            'items' =>  [
                [
                    'title' =>  __("My Profile"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $user = Auth::user();
        return view('admin.pages.profile.index', get_defined_vars());
    }

    public function store(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        if($request->hasFile('avatar')) {
            $data['avatar'] = (new \App\Support\Image)->FileUpload($request->avatar, 'users');
        }
        Auth::user()->update($data);
        (new \App\Support\Notification)->setTo(Auth::user()->id)->setTarget(Auth::user()->id)->setTargetType("edit_profile")->setBody(__(":item has been updated.",['item' => __('Profile')]))->build();
        return Redirect::route('admin.profile.index')->with('success', __(":item has been updated.",['item' => __('Profile')]) );
    }

    public function change_password()
    {
        $breadcrumb = [
            'title' =>  __("Change My Password"),
            'items' =>  [
                [
                    'title' =>  __("Change My Password"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.profile.change_password', get_defined_vars());
    }

    public function update_password(UpdatePasswordRequest $request)
    {
        if ( !Hash::check($request->current_password, Auth::user()->password) ) {
            return Redirect::route('admin.change_password.index')->withErrors([
                'current_password' => __('The current password is incorrect')
            ]);
        }
        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);
        (new \App\Support\Notification)->setTo(Auth::user()->id)->setTarget(Auth::user()->id)->setTargetType("change_password")->setBody(__(":item has been updated.",['item' => __('Profile')]))->build();
        return Redirect::route('admin.change_password.index')->with('success', __(":item has been updated.",['item' => __('Password')]) );

    }
}
