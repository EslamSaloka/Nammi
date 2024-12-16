<?php

namespace App\Http\Requests\API\Favorites;

use App\Support\JsonFormRequest;

class FavoritesRequest extends JsonFormRequest
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
            "club_id"  => "required|numeric|exists:users,id",
        ];
    }
}
