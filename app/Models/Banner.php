<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = "banners";

    protected $fillable = [
        'activity_id',
        'image',
    ];

    public function getModePermissions() {
        return [
            "sliders" => [
                "sliders.index",
                "sliders.create",
                "sliders.edit",
                "sliders.destroy",
            ],
        ];
    }

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

    public function activity() {
        return $this->hasOne(\App\Models\Activity::class,"id","activity_id");
    }

}
