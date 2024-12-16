<?php

namespace App\Http\Requests\API\Profile;

use App\Support\JsonFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileFireBaseTokenRequest extends JsonFormRequest
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
            'fire_base_token'  => 'required',
        ];
    }
}
