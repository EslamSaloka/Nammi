<?php

namespace App\Http\Requests\API\Auth;

use App\Support\JsonFormRequest;

class OTPRequest extends JsonFormRequest
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
            "otp"     => "required|numeric",
            "by"      => "required|in:forget,verify",
        ];
    }
}
