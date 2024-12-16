<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = "settings";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group_by',
    ];

    public function getModePermissions() {
        return [
            "settings" => [
                "settings.index",
                "settings.create",
                "settings.edit",
                "settings.destroy",
            ],
        ];
    }

    const FORM_INPUTS = [
        'application' => [
            'title' => 'Global Settings',
            'short_desc' => 'Global Settings',
            'icon' => 'edit-2',
            'form' => [
                'inputs' => [
                    [
                        'label'         => 'Application Name Arabic',
                        'name'          => 'application_name_ar',
                        'type'          => 'text',
                        'placeholder'   => 'Application Name Arabic',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Application Name English',
                        'name'          => 'application_name_en',
                        'type'          => 'text',
                        'placeholder'   => 'Application Name English',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'E-mail Address',
                        'name'          => 'email',
                        'type'          => 'email',
                        'placeholder'   => 'E-mail Address',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Phone Number',
                        'name'          => 'phone',
                        'type'          => 'number',
                        'placeholder'   => 'Phone Number',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Working Hours',
                        'name'          => 'working_hours',
                        'type'          => 'text',
                        'placeholder'   => 'Working Hours',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Main Branch Arabic',
                        'name'          => 'main_branch_ar',
                        'type'          => 'text',
                        'placeholder'   => 'Main Branch',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Main Branch English',
                        'name'          => 'main_branch_en',
                        'type'          => 'text',
                        'placeholder'   => 'Main Branch',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Application Image',
                        'name'          => 'logo',
                        'type'          => 'image',
                        'placeholder'   => 'Application Image',
                        'icon'          => 'file-text',
                    ],
                ],
            ],
        ],
        'social' => [
            'title' => 'Social Media',
            'short_desc' => 'Social Media',
            'icon' => 'twitter',
            'form' => [
                'inputs' => [
                    [
                        'label'         => 'facebook',
                        'name'          => 'facebook',
                        'type'          => 'url',
                        'placeholder'   => 'facebook Url',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'twitter',
                        'name'          => 'twitter',
                        'type'          => 'url',
                        'placeholder'   => 'twitter Url',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'instagram',
                        'name'          => 'instagram',
                        'type'          => 'url',
                        'placeholder'   => 'instagram Url',
                        'icon'          => 'file-text',
                    ]
                ]
            ]
        ],
        'home' => [
            'title' => 'WebSite Home Setting',
            'icon' => 'home',
            'form' => [
                'inputs' => [
                    [
                        'label'         => 'Title Arabic',
                        'name'          => 'home_title_ar',
                        'placeholder'   => 'Enter title ',
                        'type'          => 'text',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Title English',
                        'name'          => 'home_title_en',
                        'placeholder'   => 'Enter title English',
                        'type'          => 'text',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Home Content Arabic',
                        'name'          => 'home_content_ar',
                        'placeholder'   => 'Enter Content AR',
                        'type'          => 'textarea',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Home Content English',
                        'name'          => 'home_content_en',
                        'placeholder'   => 'Enter Content English',
                        'type'          => 'textarea',
                        'icon'          => 'file-text',
                    ],
                    /*[
                        'label'         => 'Home URL',
                        'name'          => 'home_url',
                        'type'          => 'url',
                        'placeholder'   => 'Enter Home URL',
                        'icon'          => 'file-text',
                    ],*/
                    [
                        'label'         => 'Home Image',
                        'name'          => 'home_image',
                        'type'          => 'file',
                        'placeholder'   => 'Enter Home Image',
                        'icon'          => 'file-text',
                    ],
                ],
            ],
        ],
        'downloads' => [
            'title' => 'Application Download Links',
            'icon' => 'link',
            'form' => [
                'inputs' => [
                    [
                        'label'         => 'Google Play',
                        'name'          => 'google_play',
                        'placeholder'   => 'Google Play ',
                        'type'          => 'url',
                        'icon'          => 'file-text',
                    ],
                    [
                        'label'         => 'Apple Store',
                        'name'          => 'apple_store',
                        'placeholder'   => 'Apple Store ',
                        'type'          => 'url',
                        'icon'          => 'file-text',
                    ],
                    /*[
                        'label'         => 'Huawei',
                        'name'          => 'huawei',
                        'placeholder'   => 'Huawei ',
                        'type'          => 'url',
                        'icon'          => 'file-text',
                    ],*/
                ],
            ],
        ],
    ];
}
