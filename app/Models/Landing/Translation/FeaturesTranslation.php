<?php

namespace App\Models\Landing\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturesTranslation extends Model
{
    use HasFactory;

    protected $table = 'landing_features_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'content',
    ];
}
