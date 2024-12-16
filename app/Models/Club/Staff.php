<?php

namespace App\Models\Club;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = "club_staffs";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'club_id',
        "user_id",
    ];

    public function user() {
        return $this->hasOne(\App\Models\User::class,"id","user_id");
    }

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }
}
