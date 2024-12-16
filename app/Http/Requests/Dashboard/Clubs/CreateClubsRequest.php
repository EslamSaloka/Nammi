<?php

namespace App\Http\Requests\Dashboard\Clubs;

use Illuminate\Foundation\Http\FormRequest;

class CreateClubsRequest extends FormRequest
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
            'name_en'       => 'required|string|max:30|unique:users,name_en',
            'about'         => 'required|string',
            'about_en'      => 'required|string',
            // ======================================== //
            'logo'          => 'required|image',
            'images'        => 'required|array',
            'images.*'      => 'required|image',
            // ======================================== //
            'categories'        => 'required|array',
            'categories.*'      => 'required|exists:categories,id',
            // ======================================== //
            'email'         => 'required|email|max:250|unique:users,email',
            'phone'         => ['required', 'numeric', 'unique:users,phone'],
            'password'      => ['required', 'max:20'],
            'suspend'       => 'nullable|boolean'
        ];
    }
}
