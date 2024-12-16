<?php

namespace App\Http\Controllers\API\Home;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Home\HomeRequest;
// Resources
use App\Http\Resources\API\Banner\BannerResource;
use App\Http\Resources\API\Categories\CategoriesResource;
use App\Http\Resources\API\Club\ShortClubNearMeResource;
use App\Http\Resources\API\Club\ShortClubResource;
// Models
use App\Models\Banner;
use App\Models\Order;
use App\Models\User;
// Support
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(HomeRequest $request) {
        $data["banners"]        = BannerResource::collection(Banner::all());
        $data["interests"]      = $this->getInterests();
        $data["recommended"]    = $this->getRecommended();
        $data["nearYou"]        = $this->getNearYou($request->lat,$request->lng);
        return (new \App\Support\API)->isOk(__("Home Screen"))->setData($data)->build();
    }

    private function getInterests(){
        return CategoriesResource::collection(getParentCategories(false));
    }

    private function getRecommended() {
        if(Auth::check()) {
            $clubs = User::whereHas("roles",function($q){
                return $q->where("name",User::TYPE_CLUB);
            })->whereHas("clubBranches",function($q){
                return $q->api();
            })->take(8)->get();
            return ShortClubResource::collection($clubs);
        }
        return [];
    }

    private function getNearYou($lat,$lng) {
        $ids = nearClubsLocation($lat,$lng);
        $clubs = User::whereHas("roles",function($q){
            return $q->where("name",User::TYPE_CLUB);
        })->whereHas("clubBranches",function($q){
            return $q->api();
        })->whereIn("id",$ids)->get();
        return ShortClubNearMeResource::collection($clubs);
    }
}
