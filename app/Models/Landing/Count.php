<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Count extends Model {

    use Translatable;

    protected $with =[
        'translations'
    ];

    protected $translationForeignKey = "count_id";

    public $translatedAttributes = ['name'];

    protected $table = "landing_counts";

    protected $fillable = ["image","count"];

    public $translationModel = 'App\Models\Landing\Translation\CountTranslation';

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
    ];

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

}

