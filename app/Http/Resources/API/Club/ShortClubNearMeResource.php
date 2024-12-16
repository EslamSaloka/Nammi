<?php

namespace App\Http\Resources\API\Club;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortClubNearMeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'rates'         => $this->rates,
            'images'        => ClubImageResource::collection($this->clubImages),
            "branch"        => $this->getBranch($request),
            "is_fav"        => isFav(((\Auth::check()) ? \Auth::user() : null),\App\Models\User::find($this->id)),
        ];
    }

    private function getBranch(Request $request) {
        $country    = $request->header('Country') ?? 0;
        $city       = $request->header('city') ?? 0;
        $branch = $this->clubBranches()->where(["country_id"=>$country,"city_id"=>$city])->first();
        if(is_null($branch)) {
            return [];
        }
        //return new ClubBranchResource($branch);
        return ClubBranchResource::collection([$branch]);
    }
}
