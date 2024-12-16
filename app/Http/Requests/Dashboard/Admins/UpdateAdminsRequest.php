<?php

namespace App\Http\Requests\Dashboard\Admins;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminsRequest extends FormRequest
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
        $id = $this->admin->id;
        return [
            "role"      => "required|string|exists:roles,id",
            'name'      => 'required|string|max:30|unique:users,name,'.$id.',id',
            'email'     => 'required|email|max:250|unique:users,email,'.$id.',id',
            'phone'     => ['required', 'numeric','unique:users,phone,'.$id.',id',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'password'  => ['nullable', 'max:20', 'regex:/^\S*(?=\S{8,})(?=\S*[a-zA-Zي-أ])(?=\S*[0-9])(?=\S*[!@#$%^&*])\S*$/'],
            'avatar'    => 'nullable|image|max:10240',
            'suspend'   => 'nullable|boolean'
        ];
    }
}
