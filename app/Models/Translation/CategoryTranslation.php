<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'category_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
