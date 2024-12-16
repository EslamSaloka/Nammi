<?php

namespace App\Http\Resources\API\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "global"   => [
                "application_name"  => getSettings("application_name",env('APP_NAME')),
                "email"             => getSettings("email","info@example.com"),
                "phone"             => getSettings("phone","96651234567"),
            ],
            "social"   => [
                "facebook"      => getSettings("facebook",env('APP_URL')),
                "twitter"       => getSettings("twitter",env('APP_URL')),
                "linkedin"      => getSettings("linkedin",env('APP_URL')),
                "instagram"     => getSettings("instagram",env('APP_URL')),
            ],
        ];
    }
}
