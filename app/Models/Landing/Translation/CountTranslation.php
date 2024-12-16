<?php

namespace App\Models\Landing\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountTranslation extends Model
{
    use HasFactory;

    protected $table = 'landing_count_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
