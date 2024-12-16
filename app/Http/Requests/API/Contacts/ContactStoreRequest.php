<?php

namespace App\Http\Requests\API\Contacts;

use App\Support\JsonFormRequest;

class ContactStoreRequest extends JsonFormRequest
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
            "name"  => "required",
            "email"  => "required|email",
            "phone"  => ["required","numeric","regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            "message"  => "required",
        ];
    }
}
