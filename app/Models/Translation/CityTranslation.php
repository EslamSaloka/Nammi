<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    use HasFactory;

    protected $table = 'city_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
