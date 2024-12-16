<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
            'phone' => "required|numeric|exists:users,phone",
        ];
    }

    public function messages() {
        return [
            "phone.required"    => __("Please enter mobile number"),
            "phone.numeric"     => __("We are sorry but the mobile number answers that it is a number"),
            "phone.exists"      => __("Mobile number not registered with us"),
        ];
    }
}
