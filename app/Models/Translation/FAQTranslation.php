<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQTranslation extends Model
{
    use HasFactory;

    protected $table = 'faq_translations';
    public $timestamps = false;
    protected $fillable = [
        'question',
        'answer',
    ];
}
