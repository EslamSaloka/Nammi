<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Settings\SettingResource;

class SettingsController extends Controller
{
    public function index() {
        return (new \App\Support\API)->isOk(__("Settings Lists"))->setData(new SettingResource([]))->build();
    }
}
