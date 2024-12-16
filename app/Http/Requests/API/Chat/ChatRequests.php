<?php

namespace App\Http\Requests\API\Chat;

use App\Support\JsonFormRequest as FormRequest;

class ChatRequests extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'club_id'   => 'required|numeric|exists:users,id',
            'message'   => 'required',
        ];
    }
}
