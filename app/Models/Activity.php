<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Activity extends Model
{
    use HasFactory,Translatable;

    protected $table = "activities";

    protected $with =[
        'translations'
    ];
    protected $translationForeignKey = "activity_id";
    public $translatedAttributes = ['name',"description"];
    public $translationModel = 'App\Models\Translation\ActivityTranslation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'club_id',
        'branch_id',
        // ==================== //
        'image',
        // ==================== //
        'price',
        // ==================== //
        'offer',
        'start_offer',
        'end_offer',
        'customer_count',
        // ==================== //
        'disabilities',
        'payment_types',
        // ==================== //
        'rates',
        'order_one_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_offer'   => 'datetime',
        'end_offer'     => 'datetime',
        'payment_types' => "array",
    ];

    public function getModePermissions() {
        return [
            "activities" => [
                "activities.index",
                "activities.create",
                "activities.edit",
                "activities.destroy",
                "activities.export",
            ],
            "activityRate" => [
                "activities.rates",
                "activities.confirmed",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Activities Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
        [
            'label'         => 'Activities Description',
            'name'          => 'description',
            'type'          => 'textarea',
            'required'      => true
        ],
    ];


    public function scopeSearch($query, $data)
    {
        if (isset($data['name']) && $data['name'] != "-1") {
            $query = $query->whereTranslationLike("name","%".request("name")."%");
        }
        if (isset($data['club_id']) && $data['club_id'] != "-1") {
            $query = $query->where('club_id',$data['club_id']);
        }

        if(request()->has("club_id") && request("club_id") != "-1") {
            $query = $query->whereHas("branch",function($q){
                return $q->where("club_id",request("club_id"));
            });
        }
        if (isset($data['disabilities']) && $data['disabilities'] != "-1") {
            $query = $query->where('disabilities',$data['disabilities']);
        }
        if (isset($data['order_one_time']) && $data['order_one_time'] != "-1") {
            $query = $query->where('order_one_time',$data['order_one_time']);
        }
        return $query;
    }

    public function showDisabilities() {
        if($this->disabilities == 0) {
            return '<span class="badge bg-pill rounded-pill bg-danger-transparent">'.__(ucwords(str_replace("_"," ","No"))).'</span>';
        } else {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ","Yes"))).'</span>';
        }
    }

    public function showOrderOneTime() {
        if($this->order_one_time == 0) {
            return '<span class="badge bg-pill rounded-pill bg-danger-transparent">'.__(ucwords(str_replace("_"," ","No"))).'</span>';
        } else {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ","Yes"))).'</span>';
        }
    }

    public function scopeApi($query)
    {
        return $query->whereHas("branch",function($q){
            return $q->where(["country_id"=>request()->header('Country') ?? 0,"city_id"=>request()->header('city') ?? 0]);
        });
    }

    public function getDisplayImageAttribute() {
        return (new \App\Support\Image)->displayImageByModel($this,"image");
    }

    public function getOfferPercentageAttribute() {
        if($this->offer == 0) {
            return 0;
        }
        return round( ( $this->price - $this->offer ) / $this->price * 100 );
    }

    public function categories() {
        return $this->belongsToMany(\App\Models\Category::class, 'activate_categories_pivot', 'activity_id' ,'category_id');
    }

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function category() {
        return $this->hasOne(\App\Models\Category::class,"id","category_id");
    }

    public function branch() {
        return $this->hasOne(\App\Models\Club\Branch::class,"id","branch_id");
    }

    public function getRates() {
        return $this->hasMany(\App\Models\Activity\Rate::class,"activity_id","id");
    }
}
