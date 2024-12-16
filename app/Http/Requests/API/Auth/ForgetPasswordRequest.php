<?php

namespace App\Http\Requests\API\Auth;

use App\Support\JsonFormRequest;

class ForgetPasswordRequest extends JsonFormRequest
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
            "phone"     => ["required","regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u","exists:users,phone","numeric"],
        ];
    }
}
