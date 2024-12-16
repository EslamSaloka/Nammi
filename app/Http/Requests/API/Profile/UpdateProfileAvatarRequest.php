<?php

namespace App\Http\Requests\API\Profile;

use App\Support\JsonFormRequest;

class UpdateProfileAvatarRequest extends JsonFormRequest
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
            'avatar'        => 'required|image|max:10240',
        ];
    }
}
