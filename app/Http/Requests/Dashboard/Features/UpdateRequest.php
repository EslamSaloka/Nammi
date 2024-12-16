<?php

namespace App\Http\Requests\Dashboard\Features;

use Illuminate\Foundation\Http\FormRequest;


class UpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    public function attributes() {
        $lang = [
            'name',
            'content',
        ];
        $return = [

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
           'name'     => 'required|string|max:150',
           'content'  => 'required',
        ];
        $rules = [
            //
        ];
        if(request()->has("image")) {
            $rules["image"] = "required|image";
        }
        foreach(config('laravellocalization.supportedLocales') as $key=>$value) {
            foreach($lang as $K=>$V) {
                $rules[$key.".".$K] = $V;
            }
        }
        return $rules;
    }
}