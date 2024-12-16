<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class FAQ extends Model
{
    use HasFactory, Translatable;

    protected $table = "faq";
    protected $with = [
        'translations'
    ];
    protected $translationForeignKey = "faq_id";
    public $translatedAttributes = ['question','answer'];
    protected $fillable = ['active'];
    public $translationModel = 'App\Models\Translation\FAQTranslation';

    public function getModePermissions() {
        return [
            "faqs" => [
                "faqs.index",
                "faqs.create",
                "faqs.edit",
                "faqs.destroy",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Question',
            'name'          => 'question',
            'type'          => 'text',
            'required'      => true
        ],
        [
            'label'         => 'Answer',
            'name'          => 'answer',
            'type'          => 'textarea',
            'required'      => true
        ],
    ];
}
