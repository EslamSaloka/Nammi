<?php

namespace App\Http\Requests\Dashboard\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
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
    public function rules() {
        return [
            "order_status"  => "required",
            "date"          => "required_if:order_status,time_change",
            "time"          => "required_if:order_status,time_change",
        ];
    }
}
