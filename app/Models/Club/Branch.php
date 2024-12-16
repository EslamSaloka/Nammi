<?php

namespace App\Models\Club;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Branch extends Model
{
    use HasFactory, Translatable;

    protected $translationForeignKey = "branch_id";

    public $translatedAttributes = ['name',"address"];

    public $translationModel = 'App\Models\Club\Translation\BranchTranslation';

    protected $table = "club_branches";

    protected $fillable = [
        'club_id',
        'user_id',
        'country_id',
        'city_id',
        // ===================//
        'email',
        'phone',
        'what_app',
        'lat',
        'lng',
    ];

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
        [
            'label'         => 'Address',
            'name'          => 'address',
            'type'          => 'text',
            'required'      => true
        ],
    ];


    public function scopeSearch($query, $data)
    {
        if( isset($data['name']) && $data['name'] != '' && $data['name'] ) {
            $query = $query->whereTranslationLike('name',"%".$data['name']."%");
        }
        if (isset($data['club_id']) && $data['club_id'] != "-1") {
            $query = $query->where('club_id',$data['club_id']);
        }
        if (isset($data['seen']) && $data['seen'] != "-1") {
            $query = $query->where('seen',$data['seen']);
        }
        return $query;
    }

    public function scopeApi($query)
    {
        $country    = request()->header('Country') ?? 0;
        $city       = request()->header('city') ?? 0;
        return $query->where(["country_id"=>$country,"city_id"=>$city]);
    }

    public function club() {
        return $this->hasOne(\App\Models\User::class,"id","club_id");
    }

    public function user() {
        return $this->hasOne(\App\Models\User::class,"id","user_id");
    }

    public function country() {
        return $this->hasOne(\App\Models\Country::class,"id","country_id");
    }

    public function city() {
        return $this->hasOne(\App\Models\City::class,"id","city_id");
    }
}
