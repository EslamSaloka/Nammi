<?php

namespace App\Http\Requests\Dashboard\Admins;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminsRequest extends FormRequest
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
            "role"      => "required|string|exists:roles,id",
            'name'      => 'required|string|max:30|unique:users,name',
            'email'     => 'required|email|max:250|unique:users,email',
            'phone'     => ['required', 'numeric', 'unique:users,phone',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'password'  => ['required', 'max:20'],
            'avatar'    => 'nullable|image|max:10240',
            'suspend'   => 'nullable|boolean'
        ];
    }
}
