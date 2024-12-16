<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class City extends Model
{
    use HasFactory, Translatable;

    protected $table = "cities";

    protected $with =[
        'translations'
    ];
    protected $translationForeignKey = "city_id";
    public $translatedAttributes = ['name'];
    protected $fillable = [
        "country_id",
    ];
    public $translationModel = 'App\Models\Translation\CityTranslation';

    public function getModePermissions() {
        return [
            "cities" => [
                "cities.index",
                "cities.create",
                "cities.show",
                "cities.edit",
                "cities.destroy",
                "cities.export",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'City Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
    ];


    public function country() {
        return $this->hasOne(\App\Models\Country::class, 'id' ,'country_id');
    }
}
