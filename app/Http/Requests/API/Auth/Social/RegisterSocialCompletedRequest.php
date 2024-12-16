<?php

namespace App\Http\Requests\API\Auth\Social;

use App\Support\JsonFormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterSocialCompletedRequest extends JsonFormRequest
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
            "name"          => "required|string|max:100",
            "email"         => "required|email|unique:users,email,".Auth::user()->id.",id",
            "phone"         => "required|numeric|unique:users,phone,".Auth::user()->id.",id",
            "password"  => [
                'required',
                'min:8',
            ],
            "birthday"      => "required|date",
            "gender"        => "required|in:male,female",
            "categories"    => "required|array",
            "categories.*"  => "required|exists:categories,id",
            "time_id"       => "required|exists:time_hobbies,id",
            "disabilities"  => "required|boolean",
            "terms"         => "required|boolean",
        ];
    }
}
