<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\API\Favorites\FavoritesRequest;
// Resources
use App\Http\Resources\API\Club\ClubResource;
use App\Models\Club\Fav;
use App\Models\User;
// Support
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function index() {
        $ids    = Auth::user()->fav()->pluck("club_id")->toArray();
        $clubs  = User::whereIn("id",$ids)->get();
        return (new \App\Support\API)->isOk(__("Clubs Favorites Lists"))
            ->setData(ClubResource::collection($clubs))
            ->build();
    }

    public function store(FavoritesRequest $request) {

        $club = User::find($request->club_id);
        if(is_null($club) || !in_array(User::TYPE_CLUB,$club->roles()->pluck("name")->toArray())) {
            return (new \App\Support\API)->isError(__("This club not found"))->setErrors([
                "club_id" => __("This club not found")
            ])->build();
        }

        $fav = Fav::where([
            "user_id"   => Auth::user()->id,
            "club_id"   => $request->club_id,
        ])->first();
        if(is_null($fav)) {
            Fav::create([
                "user_id"   => Auth::user()->id,
                "club_id"   => $request->club_id,
            ]);
            return (new \App\Support\API)->isOk(__("This Club Added From Fav"))->build();
        }
        $fav->delete();
        return (new \App\Support\API)->isOk(__("This Club Remove From Fav"))->build();
    }
}
