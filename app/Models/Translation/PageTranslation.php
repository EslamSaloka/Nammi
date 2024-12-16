<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory;

    protected $table = 'page_translations';
    public $timestamps = false;
    protected $fillable = [
        'content',
    ];
}
