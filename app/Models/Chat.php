<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'club_id',
        'updated_at',
    ];

    public function customer() {
        return $this->hasOne(\App\Models\User::class,"id","customer_id");
    }

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function messages() {
        return $this->hasMany(\App\Models\Chat\Message::class,"chat_id","id");
    }

    public function getModePermissions() {
        return [
            "chats" => [
                "chats.index",
                "chats.show",
                "chats.edit",
                "chats.destroy",
            ]
        ];
    }

}
