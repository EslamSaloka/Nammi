<?php

namespace App\Http\Requests\API\Auth;

use App\Support\JsonFormRequest;

class RegisterRequest extends JsonFormRequest
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
            "email"         => "required|email|unique:users,email",
            "phone"         => ["required","numeric","unique:users,phone","regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            "password"      => "required|min:8",
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
