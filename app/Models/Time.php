<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
class Time extends Model
{
    use HasFactory, Translatable;

    protected $table = "time_hobbies";
    protected $with = [
        'translations'
    ];
    protected $translationForeignKey = "time_id";
    public $translatedAttributes = ['name'];
    protected $fillable = ['active'];
    public $translationModel = 'App\Models\Translation\TimeTranslation';

    public function getModePermissions() {
        return [
            "times" => [
                "times.index",
                "times.create",
                "times.edit",
                "times.destroy",
            ],
        ];
    }

    const LOCALIZATION_INPUTS = [
        [
            'label'         => 'Time Name',
            'name'          => 'name',
            'type'          => 'text',
            'required'      => true
        ],
    ];
}
