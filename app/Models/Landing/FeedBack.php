<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class FeedBack extends Model {

    use Translatable;

    protected $with =[
        'translations'
    ];

    protected $translationForeignKey = "testimonial_id";

    public $translatedAttributes = ['name',"content"];

    public $translationModel = 'App\Models\Landing\Translation\FeedBackTranslation';

    protected $table = "testimonials";

    protected $fillable = [
        'star',
    ];

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
}

