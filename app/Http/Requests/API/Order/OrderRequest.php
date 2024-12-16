<?php

namespace App\Http\Requests\API\Order;

use App\Support\JsonFormRequest;

class OrderRequest extends JsonFormRequest
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
            "club_id"       => "required|numeric|exists:users,id",
            "activity_id"   => "required|numeric|exists:activities,id",
            "branch_id"     => "required|numeric|exists:club_branches,id",
            "coupon"        => "nullable",
            "name"          => "required|string|max:100",
            "mobile"        => ["required","numeric","regex:/(^(\+201|01|00201)[0-2,5]{1}[0-9]{8})/u"],
            "date"          => "required|date",
            "time"          => "required",
            "notes"         => "nullable|string|max:200",
            "payment_type"  => "required|in:visa,cod",
        ];
    }
}
