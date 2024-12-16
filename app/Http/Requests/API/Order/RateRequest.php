<?php

namespace App\Http\Requests\API\Order;

use App\Support\JsonFormRequest;

class RateRequest extends JsonFormRequest
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
            "club_rate"                => "required|numeric|between:1,5",
            "club_rate_message"        => "nullable",
            // =================================== //
            "activity_rate"             => "required|numeric|between:1,5",
            "activity_rate_message"     => "nullable",
        ];
    }
}
