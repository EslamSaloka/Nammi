<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Features extends Model {

    use Translatable;

    protected $with =[
        'translations'
    ];

    protected $translationForeignKey = "features_id";

    public $translatedAttributes = ['name',"content"];

    protected $table = "landing_features";

    protected $fillable = ["image"];

    public $translationModel = 'App\Models\Landing\Translation\FeaturesTranslation';

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
        [
            'label'         => 'Content',
            'name'          => 'content',
            'type'          => 'textarea',
            'required'      => true
        ],
    ];

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

}

