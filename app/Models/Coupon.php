<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = "coupons";
    protected $fillable = [
        'name',
        'value',
        'type',
        'expire',
    ];

    public function getModePermissions() {
        return [
            "coupons" => [
                "coupons.index",
                "coupons.create",
                "coupons.edit",
                "coupons.destroy",
            ],
        ];
    }
}
