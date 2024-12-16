<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTranslation extends Model
{
    use HasFactory;

    protected $table = 'activity_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
    ];
}
