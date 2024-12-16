<?php

namespace App\Http\Requests\Dashboard\Clubs\Activities;

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

    public function attributes() {
        $lang = [
            'name',
            'description',
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
            'name'              => 'required|string|max:150',
            'description'       => 'required|string',
        ];
        $rules = [
            'branch_id'             => 'required|numeric|exists:club_branches,id',
            'main_category'         => 'required|numeric|exists:categories,id',
            'sub_category'          => 'required|numeric|exists:categories,id',
            'image'                 => 'required|image',
            'price'                 => 'required|numeric',
            'offer'                 => 'required|numeric',
            'start_offer'           => "required_if:offer,>,1",
            'end_offer'             => 'required_if:offer,>,1',
            'customer_count'        => 'required_if:offer,>,1',
            'disabilities'          => 'required|boolean',
            'payment_types'         => 'required|array',
            'order_one_time'        => 'required|boolean',
         ];
         foreach(config('laravellocalization.supportedLocales') as $key=>$value) {
             foreach($lang as $K=>$V) {
                 $rules[$key.".".$K] = $V;
             }
         }
         if(isAdmin()) {
            $rules["club_id"] = 'required|numeric|exists:users,id';
        }
        return $rules;
    }
}
