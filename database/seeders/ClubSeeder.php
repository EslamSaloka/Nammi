<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\City;
use App\Models\Club\Branch;
use App\Models\Country;
use App\Models\Time;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = User::where([
            "email" => "club@example.com"
        ])->first();
        if(is_null($club)) {
            $club = User::create([
                'name'              =>  "نادي النصر الرياضي",
                "name_en"           => "Club",
                "email"             =>  "club@example.com",
                'phone'             =>  "966512345677",
                'phone_verified_at' =>  Carbon::now(),
                'accepted_at'       =>  Carbon::now(),
                'password'          =>  Hash::make("123456789"),
                'time_id'           =>  Time::first()->id ?? 0,
                "about"             => "about arabic",
                "about_en"          => "about english",
            ]);
        }
        $club->assignRole(User::TYPE_CLUB);
        $club->categories()->sync([1,2,3]);
        $club->clubImages()->create([
            "image" => "uploads/club/1.png"
        ]);
        $club->clubBranches()->create([
            'country_id'    => Country::first()->id,
            'city_id'       => City::first()->id,
            'email'         => "branch@ss.com",
            'phone'         => "9661236549",
            'what_app'      => "9661236549",
            'address'       => "qaz xsw edc",
            'lat'           => 23.33333,
            'lng'           => 32.22222,
        ]);

        // =========================================================== //
        if(Activity::count() == 0) {
            for($i = 0; $i < 10; $i++) {
                $acc = Activity::create([
                    "ar"            =>[
                        "name"          => "Activity-NAME-AR-".$i,
                        "description"   => "EN-DESCRIPTION-".$i,
                    ],
                    "en"            =>[
                        "name"          => "Activity-NAME-EN-".$i,
                        "description"   => "EN-DESCRIPTION-".$i,
                    ],
                    'club_id'       => $club->id,
                    // 'country_id'    => Country::first()->id,
                    // 'city_id'       => City::first()->id,
                    'branch_id'     => Branch::where(["club_id"=> $club->id])->first()->id,
                    'price'         => 100,
                    'disabilities'  => 0,
                    'payment_types' => ["visa"],
                ]);
                $acc->categories([1,2,3,4,5]);
            }
        }
    }
}
