<?php

namespace App\Http\Requests\Dashboard\Clubs\Branches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name',
            'address',
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
    public function rules()
    {
        $lang = [
            'name'          => 'required|string|max:150',
            'address'       => 'required|string',
        ];
        $rules = [
            'country_id'   => 'required|numeric|exists:countries,id',
            'city_id'      => 'required|numeric|exists:cities,id',
            'user_id'      => 'required|numeric|exists:users,id',
            'email'        => 'required|email|unique:club_branches,email,'.$this->branch->id,',id',
            'phone'        => ['required','numeric',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'what_app'     => ['required',"regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            'lat'          => 'required|numeric',
            'lng'          => 'required|numeric',
        ];
        if(isAdmin()) {
            $rules["club_id"] = 'required|numeric|exists:users,id';
        }
        foreach(config('laravellocalization.supportedLocales') as $key=>$value) {
            foreach($lang as $K=>$V) {
                $rules[$key.".".$K] = $V;
            }
        }
        return $rules;
    }
}
