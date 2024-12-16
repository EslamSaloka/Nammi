<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Time\TimeResource;

class TimeOfPlayHobbyController extends Controller
{
    public function index() {
        return (new \App\Support\API)->isOk(__("Time Of Hobby Lists"))->setData(TimeResource::collection(getTimeHobby()))->build();
    }
}
