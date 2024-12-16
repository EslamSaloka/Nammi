<?php

namespace App\Http\Requests\API\Auth\Social;

use App\Support\JsonFormRequest;

class RegisterSocialRequest extends JsonFormRequest
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
            "name"         => "required|string|max:100",
            "account_by"   => "required",
            "social_id"    => "required|numeric",
        ];
    }
}
