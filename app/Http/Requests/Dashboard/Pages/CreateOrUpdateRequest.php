<?php

namespace App\Http\Requests\Dashboard\Pages;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateRequest extends FormRequest
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

    public function attributes() {
        $lang = [
            'content',
        ];
        $return = [
            //
        ];
        foreach(config('laravellocalization.supportedLocales') as $key=>$value) {
            foreach($lang as $V) {
                $return[$key.".".$V] = __($key.".".$V);
            }
        }
        return $return;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $lang = [
           'content'    => 'required|string',
        ];
        $rules = [
            //
        ];
        foreach(config('laravellocalization.supportedLocales') as $key=>$value) {
            foreach($lang as $K=>$V) {
                $rules[$key.".".$K] = $V;
            }
        }
        return $rules;
    }
}
