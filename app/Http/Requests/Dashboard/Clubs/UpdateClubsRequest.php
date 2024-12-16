<?php

namespace App\Http\Requests\Dashboard\Clubs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClubsRequest extends FormRequest
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
        $id = $this->club->id;
        return [
            'name'          => 'required|string|max:30|unique:users,name,'.$id.',id',
            'name_en'       => 'required|string|max:30|unique:users,name_en,'.$id.',id',
            'about'         => 'required|string',
            'about_en'      => 'required|string',
            // ======================================== //
            'logo'          => 'nullable|image',
            'images'        => 'nullable|array',
            'images.*'      => 'nullable|image',
            // ======================================== //
            'categories'        => 'required|array',
            'categories.*'      => 'required|exists:categories,id',
            // ======================================== //
            'email'  => 'required|email|max:250|unique:users,email,'.$id.',id',
            'phone'  => ['required', 'numeric','unique:users,phone,'.$id.',id'],
            'password' => ['nullable', 'max:20'],
            'suspend' => 'nullable|boolean'
        ];
    }
}
