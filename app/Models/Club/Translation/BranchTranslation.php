<?php

namespace App\Models\Club\Translation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchTranslation extends Model
{
    use HasFactory;

    protected $table = 'branch_translations';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'address',
    ];
}
