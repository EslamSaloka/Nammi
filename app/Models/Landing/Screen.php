<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screen extends Model
{
    use HasFactory;

    protected $table = "application_images";

    protected $fillable = [
        'image',
    ];

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }
}

