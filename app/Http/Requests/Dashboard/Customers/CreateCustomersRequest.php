<?php

namespace App\Http\Requests\Dashboard\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomersRequest extends FormRequest
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
            'name'          => 'required|string|max:30|unique:users,name',
            'email'         => 'required|email|max:250|unique:users,email',
            'phone'         => ['required','numeric','unique:users,phone',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'password'      => 'required|max:20',
            'avatar'        => 'nullable|image|max:10240',
            'suspend'       => 'nullable|boolean',
            "birthday"      => "required",
            "gender"        => "required|in:male,female",
            "disabilities"  => "required|boolean",
            "time_id"       => "required|exists:time_hobbies,id",
        ];
    }
}
