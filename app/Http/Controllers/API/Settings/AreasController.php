<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
// Resources
use App\Http\Resources\API\Country\AreaPluckResource;
// Models
use App\Models\Country;

class AreasController extends Controller
{
    public function index() {
        return (new \App\Support\API)->isOk(__("Area Lists"))
            ->setData(AreaPluckResource::collection(Country::all()))
            ->build();
    }
}
