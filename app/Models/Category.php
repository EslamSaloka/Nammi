<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Category extends Model
{
    use HasFactory, Translatable;

    protected $table = "categories";

    protected $with =[
        'translations'
    ];
    protected $translationForeignKey = "category_id";
    public $translatedAttributes = ['name'];
    protected $fillable = [
        "parent_id",
        "active",
        "hexacode_color",
        "image",
    ];
    public $translationModel = 'App\Models\Translation\CategoryTranslation';

    public function getModePermissions() {
        return [
            "categories" => [
                "categories.index",
                "categories.create",
                "categories.show",
                "categories.edit",
                "categories.destroy",
                "categories.export",
            ],
            "chiders" => [
                "chiders.index",
                "chiders.create",
                "chiders.edit",
                "chiders.destroy",
                "chiders.export",
            ],
            "banners" => [
                "banners.index",
                "banners.create",
                "banners.edit",
                "banners.destroy",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Category Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
    ];

    public function scopeSearch($query, $data)
    {
        $query = $query->where("parent_id",0);
        if (isset($data['name']) && $data['name'] != "") {
            $query = $query->whereTranslationLike('name',"%".$data['name']."%");
        }
        return $query;
    }

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

    public function children() {
        return $this->hasMany(self::class,"parent_id","id");
    }

    public function parent() {
        return $this->hasOne(self::class,"id","parent_id");
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'user_categories_pivot', 'category_id' ,'user_id');
    }

    public function banners() {
        return $this->hasMany(\App\Models\Category\Image::class,"category_id","id");
    }
}
