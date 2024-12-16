<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use HasFactory;

    protected $table = "dues";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'club_id',
        'order_id',
        "price",
        "confirmed",
        "order_by",
        "order_total_price",
    ];

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function order() {
        return $this->hasOne(\App\Models\Order::class,"id","order_id");
    }

    public function showConfirmed() {
        if($this->confirmed == 0) {
            return '<span class="badge bg-pill rounded-pill bg-danger-transparent">'.__(ucwords(str_replace("_"," ","Not Completed"))).'</span>';
        } else {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ","Completed"))).'</span>';
        }
    }
}
