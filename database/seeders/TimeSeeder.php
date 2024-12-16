<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Time;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Time::count() == 0) {
            Time::create([
                "ar"    => [
                    "name"   => "3-4",
                ],
                "en"    => [
                    "name"   => "3-4",
                ],
            ]);
            Time::create([
                "ar"    => [
                    "name"   => "4-5",
                ],
                "en"    => [
                    "name"   => "4-5",
                ],
            ]);
            Time::create([
                "ar"    => [
                    "name"   => "almost everyday",
                ],
                "en"    => [
                    "name"   => "almost everyday",
                ],
            ]);
        }
    }
}
