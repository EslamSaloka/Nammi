<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'object'     => 'required',
            'password'   => [
                'required',
                'min:8'
            ],
        ];
    }

    public function messages()
    {
        return [
            'object.required'     => __('Please enter your username, mobile or email'),
        ];
    }
}
