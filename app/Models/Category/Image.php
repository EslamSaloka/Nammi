<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = "category_banners";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        "image",
    ];

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

    public function category() {
        return $this->hasOne(\App\Models\Category::class,"id","category_id");
    }
}
