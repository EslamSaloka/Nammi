<?php

namespace App\Http\Requests\API\Club;

use App\Support\JsonFormRequest;

class ClubListsSearchRequest extends JsonFormRequest
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
            "category"          => "nullable|numeric|exists:categories,id",
            "city"              => "nullable|numeric|exists:cities,id",
        ];
    }
}
