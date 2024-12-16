<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Models
use App\Models\Country;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Country::count() == 0) {
            $country = Country::create([
                "ar"    => [
                    "name"   => "مصر",
                ],
                "en"    => [
                    "name"   => "Egypt",
                ],
            ]);
            $data = (array)json_decode(trim(file_get_contents(public_path("egypt.json"))),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            foreach($data as $key=>$value) {
                $country->cities()->create([
                    "ar"    => [
                        "name"   => $value,
                    ],
                    "en"    => [
                        "name"   => $key,
                    ],
                ]);
            }
        }
    }
}
