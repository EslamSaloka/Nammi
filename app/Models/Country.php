<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Country extends Model
{
    use HasFactory, Translatable;

    protected $table = "countries";

    protected $with =[
        'translations'
    ];
    protected $translationForeignKey = "country_id";
    public $translatedAttributes = ['name'];
    protected $fillable = [
        "active",
    ];
    public $translationModel = 'App\Models\Translation\CountryTranslation';

    public function getModePermissions() {
        return [
            "countries" => [
                "countries.index",
                "countries.create",
                "countries.show",
                "countries.edit",
                "countries.destroy",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Country Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
    ];


    public function cities() {
        return $this->hasMany(\App\Models\City::class, 'country_id' ,'id');
    }
}
