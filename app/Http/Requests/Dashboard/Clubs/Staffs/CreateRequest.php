<?php

namespace App\Http\Requests\Dashboard\Clubs\Staffs;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        $rules = [
            'name'      => 'required|string|max:30|unique:users,name',
            'email'     => 'required|email|max:250|unique:users,email',
            'phone'     => ['required', 'numeric', 'unique:users,phone',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'password'  => ['required', 'max:20'],
            'avatar'    => 'required|image|max:10240',
        ];
        if(isAdmin()) {
            $rules["club_id"] = 'required|numeric|exists:users,id';
        }
        return $rules;
    }
}
