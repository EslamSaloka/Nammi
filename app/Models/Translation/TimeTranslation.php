<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTranslation extends Model
{
    use HasFactory;

    protected $table = 'time_hobby_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
