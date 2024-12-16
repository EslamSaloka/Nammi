<?php

namespace App\Http\Requests\API\Auth;

use App\Support\JsonFormRequest;

class LoginRequest extends JsonFormRequest
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
        $rul = "required|email|exists:users,email";
        if(request()->has("object")) {
            if(is_numeric(request("object"))) {
                $rul = ['required','numeric','exists:users,phone',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"];
            }
        }
        return [
            "object"    => $rul,
            "password"  => [
                'required',
                'min:8',
            ]
        ];
    }
}
