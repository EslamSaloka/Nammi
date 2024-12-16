<?php

namespace App\Http\Resources\API\Club;

// Http
use App\Http\Resources\API\Categories\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ClubResource extends JsonResource
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
            'about'         => $this->about,
            'rates'         => $this->rates,
            'images'        => ClubImageResource::collection($this->clubImages),
            'categories'    => $this->getCategories(),
            "branch"        => $this->getBranch($request),
            "is_fav"        => isFav(((\Auth::check()) ? \Auth::user() : null),\App\Models\User::find($this->id)),
        ];
    }

    private function getCategories() {
        $cats   = [];
        $i      = 0;
        foreach($this->categories()->where("parent_id",0)->get() as $item) {
            $cats[$i]               = [
                "id"        => $item->id,
                "name"      => $item->name,
                "image"     => $item->display_image,
            ];
            $cats[$i]["children"]   = [];
            $children = Category::where("parent_id",$item->id)->get();
            if(!is_null($children)) {
                $cats[$i]["children"]   = CategoriesResource::collection($children);
            }
            $i++;
        }
        return $cats;
    }

    private function getBranch(Request $request) {
        $country    = $request->header('Country') ?? 0;
        $city       = $request->header('city') ?? 0;
        if(is_null(request()->header("country"))) {
            return (is_null($this->clubBranches())) ? [] : ClubBranchResource::collection($this->clubBranches);
        } else {
            $branch = $this->clubBranches()->where(["country_id"=>$country,"city_id"=>$city])->first();
            if(is_null($branch)) {
                return [];
            }
            return ClubBranchResource::collection([$branch]);
        }
    }
}
