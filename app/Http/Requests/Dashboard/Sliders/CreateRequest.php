<?php

namespace App\Http\Requests\Dashboard\Sliders;

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
        $rules = [
            'activity_id'       => 'required|numeric|exists:activities,id',
            'image'             => 'required|image',
        ];
        return $rules;
    }
}
