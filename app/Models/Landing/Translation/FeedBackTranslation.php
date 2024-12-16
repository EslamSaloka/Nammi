<?php

namespace App\Models\Landing\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBackTranslation extends Model
{
    use HasFactory;

    protected $table = 'testimonial_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'content',
    ];
}
