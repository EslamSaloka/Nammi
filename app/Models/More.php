<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class More extends Model
{
    use HasFactory;

    public function getModePermissions() {
        return [
            "feedbacks" => [
                "feedbacks.index",
                "feedbacks.create",
                "feedbacks.edit",
                "feedbacks.destroy",
            ],
            "HomeCount" => [
                "counts.index",
                "counts.create",
                "counts.edit",
                "counts.destroy",
            ],
            "features" => [
                "features.index",
                "features.create",
                "features.edit",
                "features.destroy",
            ],
            "screens" => [
                "screens.index",
                "screens.create",
                "screens.destroy",
            ],
        ];
    }

}
