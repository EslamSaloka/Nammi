<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";

    const STATUS_PENDING      = "pending";
    const STATUS_ACCEPTED     = "accepted";
    const STATUS_TIME_CHANGE  = "time_change";
    const STATUS_CONFIRMED    = "confirmed";
    const STATUS_REJECTED     = "rejected";
    const STATUS_WAITING_CUSTOMER_COMPLETED    = "Waiting_customer_confirmation";
    const STATUS_COMPLETED    = "completed";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'club_id',
        'activity_id',
        "branch_id",
        // "country_id",
        // "city_id",
        'coupon_id',
        // =================================== //
        "sub_price",
        "coupon_price",
        "total",
        // =================================== //
        "order_status",
        "cancel_by",
        // =================================== //
        'name',
        'mobile',
        'date',
        'time',
        'notes',
        // =================================== //
        "otp",
        "otp_verified_at",
        // =================================== //
        "payment_type",
        "payment_status",
        'invoiceId',
        'transaction_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'otp_verified_at' => 'datetime',
    ];

    public function getModePermissions() {
        return [
            "orders" => [
                "orders.index",
                "orders.create",
                "orders.show",
                "orders.edit",
                "orders.destroy",
                "orders.export",
                "orders.check-otp",
                "orders.re-send",
            ],
            "rates" => [
                "rates.index",
                "rates.destroy",
                "rates.confirmed",
            ],
            "dues" => [
                "dues.index",
                "dues.show",
                "dues.confirmed",
                "dues.export",
            ],
            "revenue" => [
                "revenue.index",
                "revenue.show",
                "revenue.confirmed",
                "revenue.export",
            ],
        ];
    }


    public function scopeSearch($query, $data)
    {
        if (isset($data['id']) && $data['id'] != "-1") {
            $query = $query->where('id',$data['id']);
        }
        if (isset($data['club_id']) && $data['club_id'] != "-1") {
            $query = $query->where('club_id',$data['club_id']);
        }
        if (isset($data['customer_id']) && $data['customer_id'] != "-1") {
            $query = $query->where('customer_id',$data['customer_id']);
        }
        if (isset($data['order_status']) && $data['order_status'] != "-1") {
            $query = $query->where('order_status',$data['order_status']);
        }

        if (isset($data['from_date']) && $data['from_date']) {
            $query = $query->whereDate('created_at', '>=', $data['from_date']);
        }
        if (isset($data['to_date']) && $data['to_date']) {
            $query = $query->whereDate('created_at', '<=', $data['to_date']);
        }

        return $query;
    }

    public static function orderStatus() {
        return [
            (Object)[
                "id"       => self::STATUS_PENDING,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_PENDING)),
            ],
            (Object)[
                "id"       => self::STATUS_ACCEPTED,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_ACCEPTED)),
            ],
            (Object)[
                "id"       => self::STATUS_TIME_CHANGE,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_TIME_CHANGE)),
            ],
            (Object)[
                "id"       => self::STATUS_CONFIRMED,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_CONFIRMED)),
            ],
            (Object)[
                "id"       => self::STATUS_REJECTED,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_REJECTED)),
            ],
            (Object)[
                "id"       => self::STATUS_WAITING_CUSTOMER_COMPLETED,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_WAITING_CUSTOMER_COMPLETED)),
            ],
            (Object)[
                "id"       => self::STATUS_COMPLETED,
                "name"     => ucwords(str_replace("_"," ",self::STATUS_COMPLETED)),
            ],
        ];
    }

    public function showOrderStatus() {
        if($this->order_status == self::STATUS_PENDING) {
            return '<span class="badge bg-pill rounded-pill bg-warning">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_ACCEPTED) {
            return '<span class="badge bg-pill rounded-pill bg-success">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_TIME_CHANGE) {
            return '<span class="badge bg-pill rounded-pill bg-dark">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_CONFIRMED) {
            return '<span class="badge bg-pill rounded-pill bg-primary">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_REJECTED) {
            return '<span class="badge bg-pill rounded-pill bg-danger">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_WAITING_CUSTOMER_COMPLETED) {
            return '<span class="badge bg-pill rounded-pill bg-warning-transparent">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
        if($this->order_status == self::STATUS_COMPLETED) {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ",$this->order_status))).'</span>';
        }
    }

    public function customer () {
        return $this->hasOne(\App\Models\User::class,"id","customer_id");
    }

    public function activity () {
        return $this->hasOne(\App\Models\Activity::class,"id","activity_id");
    }

    public function branch () {
        return $this->hasOne(\App\Models\Club\Branch::class,"id","branch_id");
    }

    public function club () {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    // public function country () {
    //     return $this->hasOne(\App\Models\Country::class,"id","country_id");
    // }

    // public function city () {
    //     return $this->hasOne(\App\Models\City::class,"id","city_id");
    // }

    public function coupon () {
        return $this->hasOne(\App\Models\Coupon::class,"id","coupon_id");
    }

    public function histories () {
        return $this->hasMany(\App\Models\Order\History::class,"order_id","id");
    }
}
