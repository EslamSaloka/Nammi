<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = "order_rates";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'club_id',
        'order_id',
        // =================================== //
        "rate",
        "notes",
        "confirmed",
    ];

    public function getModePermissions() {
        return [
            "orders" => [
                "orders.index",
                "orders.create",
                "orders.edit",
                "orders.destroy",
            ],
        ];
    }

    public function showConfirmed() {
        if($this->confirmed == 0) {
            return '<span class="badge bg-pill rounded-pill bg-danger-transparent">'.__(ucwords(str_replace("_"," ","No"))).'</span>';
        } else {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ","Yes"))).'</span>';
        }
    }

    public function customer() {
        return $this->hasOne(\App\Models\User::class,"id","customer_id");
    }

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function order() {
        return $this->hasOne(\App\Models\Order::class,"id","order_id");
    }
}
