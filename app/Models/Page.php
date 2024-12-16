<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Page extends Model
{
    use HasFactory, Translatable;

    protected $table = "pages";
    protected $with = [
        'translations'
    ];
    protected $translationForeignKey = "page_id";
    public $translatedAttributes = ['content'];
    protected $fillable = ['slug'];
    public $translationModel = 'App\Models\Translation\PageTranslation';

    public function getModePermissions() {
        return [
            "pages" => [
                "pages.index",
                "pages.edit",
                "pages.destroy",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Page Content',
            'name'          => 'content',
            'type'          => 'textarea',
            'required'      => true
        ],
    ];
}
