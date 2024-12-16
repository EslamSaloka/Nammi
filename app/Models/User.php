<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;


    const TYPE_ADMIN        = "administrator";
    const TYPE_CUSTOMER     = "customer";
    const TYPE_CLUB         = "club";
    const TYPE_BRANCH       = "branch";

    protected $table = "users";
    protected $guard_name = "web";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birthday',
        'otp',
        'gender',
        'disabilities',
        'avatar',
        'suspend',
        'points',
        // ====================== //
        "phone_verified_at",
        "accepted_at",
        "rejected_at",
        "completed_at",
        // ====================== //
        "account_by",
        "social_id",
        // ====================== //
        "time_id",
        // ====================== //
        "fire_base_token",
        "last_action_at",
        // ====================== //
        "about",
        "about_en",
        "name_en",
        "rates",
        // ====================== //
        "vat",
        "rejected_message",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => "date",
    ];

    public function getModePermissions() {
        return [
            "roles" => [
                "roles.index",
                "roles.create",
                "roles.edit",
                "roles.destroy",
            ],
            "admins" => [
                "admins.index",
                "admins.create",
                "admins.edit",
                "admins.destroy",
                "admins.export",
            ],
            "customers" => [
                "customers.index",
                "customers.create",
                "customers.edit",
                "customers.destroy",
                "customers.export",
            ],
            "clubs" => [
                "clubs.index",
                "clubs.create",
                "clubs.show",
                "clubs.edit",
                "clubs.destroy",
                "clubs.export",
            ],
            "staffs" => [
                "staffs.index",
                "staffs.create",
                "staffs.edit",
                "staffs.destroy",
            ],
            "requests" => [
                "requests.index",
                "requests.show",
                "requests.accept",
                "requests.destroy",
            ],
            "branches" => [
                "branches.index",
                "branches.create",
                "branches.edit",
                "branches.destroy",
                "branches.export",
            ],
            "banners" => [
                "banners.index",
                "banners.create",
                "banners.edit",
                "banners.destroy",
            ],
            "reports" => [
                "reports.index",
            ],
        ];
    }

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"avatar",false,false,"name");
    }

    public function getNameAttribute($value) {
        if(request()->is("*api*")) {
            if(App::getLocale() == "ar") {
                return $value;
            }
            //return $this->name_en;
        }
        return $value;
    }

    public function getAboutAttribute($value) {
        if(request()->is("*api*")) {
            if(App::getLocale() == "ar") {
                return $value;
            }
            return $this->about_en;
        }
        return $value;
    }

    // ============================================== //
    public function categories() {
        return $this->belongsToMany(\App\Models\Category::class, 'user_categories_pivot', 'user_id' ,'category_id');
    }

    // ------------- Customer Data -----------------  //

    public function time() {
        return $this->hasOne(\App\Models\Time::class,"id","time_id");
    }

    public function fav() {
        return $this->hasMany(\App\Models\Club\Fav::class,"user_id","id");
    }

    public function notifications() {
        return $this->hasMany(\App\Models\Notification::class,"user_id","id");
    }

    public function orders() {
        return $this->hasMany(\App\Models\Order::class,"customer_id","id");
    }

    // ----------------- Club Data -------------------- //
    public function clubImages() {
        return $this->hasMany(\App\Models\Club\Image::class,"club_id","id");
    }

    public function clubBranches() {
        return $this->hasMany(\App\Models\Club\Branch::class,"club_id","id");
    }

    public function clubRates() {
        return $this->hasMany(\App\Models\Order\Rate::class,"club_id","id");
    }

    public function clubActivities() {
        return $this->hasMany(\App\Models\Activity::class,"club_id","id");
    }

    public function clubDues() {
        return $this->hasMany(\App\Models\Order\Due::class,"club_id","id");
    }

    public function clubStaff() {
        return $this->hasMany(\App\Models\Club\Staff::class,"club_id","id");
    }

    // ============================================== //
    public function getStaffClub() {
        return $this->hasOne(\App\Models\Club\Staff::class,"user_id","id");
    }

    // ============================================== //

    public static function clubsPermissions() {
        return [
            // Club
            "clubs.index",
            "clubs.show",
            "clubs.edit",
            // Banners
            "banners.index",
            "banners.create",
            "banners.edit",
            "banners.destroy",
            // Staffs
            "staffs.index",
            "staffs.create",
            "staffs.edit",
            "staffs.destroy",
            // Orders
            "orders.index",
            "orders.show",
            "orders.edit",
            // Rates
            "rates.index",
            "rates.destroy",
            // Activity
            "activities.index",
            "activities.create",
            "activities.edit",
            "activities.destroy",
            // Branches
            "branches.index",
            "branches.create",
            "branches.edit",
            "branches.destroy",
            // Chat
            "chats.index",
            "chats.show",
            "chats.edit",
            // Notifications
            "notifications.index",
            "notifications.show",
            "notifications.destroy",
            // Dues
            "dues.index",
        ];
    }

    // ============================================== //

    public static function staffPermissions() {
        return [
            // Orders
            "orders.index",
            "orders.show",
            "orders.edit",
            // Branches
            "branches.index",
            "branches.edit",
        ];
    }

    public function scopeSearch($query, $data)
    {
        if( isset($data['name']) && $data['name'] != '' && $data['name'] ) {
            if (filter_var($data['name'], FILTER_VALIDATE_EMAIL) ) { // search by email
                $query = $query->where('email','like','%'.$data['name'].'%');
            } else if (is_numeric($data['name'])) { //search by phone
                $query = $query->where('phone','like','%'.$data['name'].'%');
            } else { //search by name
                $query = $query->where('name','like','%'.$data['name'].'%');
            }
        }
        if (isset($data['role']) && $data['role'] > 0) {
            $query = $query->whereHas('roles', function($q) use($data) {
                $q = $q->where('role', $data['role']);
            });
        }
        if (isset($data['from_date']) && $data['from_date']) {
            $query = $query->whereDate('created_at', '>=', $data['from_date']);
        }
        if (isset($data['to_date']) && $data['to_date']) {
            $query = $query->whereDate('created_at', '<=', $data['to_date']);
        }
        return $query;
    }

}
