<?php

namespace App\Http\Requests\Dashboard\Screen;

use Illuminate\Foundation\Http\FormRequest;


class CreateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
           'images'     => 'required|array',
           'images.*'   => 'required|image',
        ];
    }
}
