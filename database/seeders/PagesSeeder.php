<?php

namespace Database\Seeders;

use App\Models\FAQ;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Page::count() == 0) {
            Page::create([
                "slug"  => "about",
                "ar"    => [
                    "content"   => "من نحن",
                ],
                "en"    => [
                    "content"   => "About",
                ],
            ]);
            // ============================== //
            Page::create([
                "slug"  => "privacy_policy",
                "ar"    => [
                    "content"   => "privacy_policy",
                ],
                "en"    => [
                    "content"   => "privacy_policy",
                ],
            ]);
            // ============================== //
            Page::create([
                "slug"  => "terms_of_services",
                "ar"    => [
                    "content"   => "terms_of_services",
                ],
                "en"    => [
                    "content"   => "terms_of_services",
                ],
            ]);
            // ============================== //
        }
        if(FAQ::count() == 0) {
            FAQ::create([
                "ar"    => [
                    "question"   => "من نحن",
                    "answer"   => "من نحن",
                ],
                "en"    => [
                    "question"   => "About",
                    "answer"   => "About",
                ],
            ]);
            FAQ::create([
                "ar"    => [
                    "question"   => "من نحن",
                    "answer"   => "من نحن",
                ],
                "en"    => [
                    "question"   => "About",
                    "answer"   => "About",
                ],
            ]);
            FAQ::create([
                "ar"    => [
                    "question"   => "من نحن",
                    "answer"   => "من نحن",
                ],
                "en"    => [
                    "question"   => "About",
                    "answer"   => "About",
                ],
            ]);
            FAQ::create([
                "ar"    => [
                    "question"   => "من نحن",
                    "answer"   => "من نحن",
                ],
                "en"    => [
                    "question"   => "About",
                    "answer"   => "About",
                ],
            ]);
        }
    }
}
