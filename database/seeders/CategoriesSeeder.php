<?php

namespace Database\Seeders;

use App\Models\FAQ;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Category::count() == 0) {
            $cat1 = Category::create([
                "ar"    => [
                    "name"   => "القسم الأول",
                ],
                "en"    => [
                    "name"   => "CAT 1",
                ],
            ]);
            for($i = 1; $i <= 20; $i++) {
                $cat1->children()->create([
                    "ar"    => [
                        "name"   => "فرع-{$cat1->id}-{$i}",
                    ],
                    "en"    => [
                        "name"   => "SUB-{$cat1->id}-{$i}",
                    ],
                ]);
            }
            // ============================== //
            $cat2 = Category::create([
                "ar"    => [
                    "name"   => "القسم الثاني",
                ],
                "en"    => [
                    "name"   => "CAT 2",
                ],
            ]);
            for($i = 1; $i <= 20; $i++) {
                $cat2->children()->create([
                    "ar"    => [
                        "name"   => "فرع-{$cat2->id}-{$i}",
                    ],
                    "en"    => [
                        "name"   => "SUB-{$cat2->id}-{$i}",
                    ],
                ]);
            }
            // ============================== //
            $cat3 = Category::create([
                "ar"    => [
                    "name"   => "القسم الثالث",
                ],
                "en"    => [
                    "name"   => "CAT 3",
                ],
            ]);
            for($i = 1; $i <= 20; $i++) {
                $cat3->children()->create([
                    "ar"    => [
                        "name"   => "فرع-{$cat2->id}-{$i}",
                    ],
                    "en"    => [
                        "name"   => "SUB-{$cat2->id}-{$i}",
                    ],
                ]);
            }
            // ============================== //
        }
    }
}
