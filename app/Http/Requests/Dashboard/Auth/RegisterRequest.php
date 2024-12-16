<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'          => 'required|string|max:200',
            'name_en'       => 'required|string|max:200',
            // ======================== //
            'about'         => 'required|string',
            'about_en'      => 'required|string',
            // ======================== //
            'email'         => 'required|email|unique:users,email',
            'phone'         => [
                "required",
                "numeric",
                "unique:users,phone",
                "regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u",
            ],
            // ======================== //
            'categories'        => 'required|array',
            'categories.*'      => 'required|exists:categories,id',
            // ======================== //
            'password'      => "required|min:8|confirmed",
            // ======================== //
            'logo'          => "required|image",
        ];
    }
}
