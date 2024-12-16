<?php

namespace App\Http\Requests\API\Auth\Register;

use App\Support\JsonFormRequest;

class RegisterPhoneCompletedRequest extends JsonFormRequest
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
            "email"         => "required|email|unique:users,email",
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
