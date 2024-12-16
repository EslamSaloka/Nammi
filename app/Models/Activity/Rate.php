<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = "activate_rates";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        "activity_id",
        "rate",
        "notes",
        "confirmed",
    ];

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

    public function activity() {
        return $this->hasOne(\App\Models\Activity::class,"id","activity_id");
    }
}
