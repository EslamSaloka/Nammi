<?php

namespace App\Models\Club;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    use HasFactory;

    protected $table = "user_club_fav";
    public $timestamps = false;
    protected $fillable = [
        'club_id',
        'user_id',
    ];

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function user() {
        return $this->hasOne(\App\Models\User::class,"id","user_id");
    }
}
