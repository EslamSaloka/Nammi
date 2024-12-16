<?php

namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|max:30|string',
            'email'         => 'required|email|unique:users,email,'.Auth::user()->id.",id",
            'phone'         => [
                "required",
                "numeric",
                "unique:users,phone,".Auth::user()->id.",id",
                "regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u",
            ],
            'avatar'        => 'nullable|image|max:10240',
        ];
    }
}
