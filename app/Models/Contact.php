<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = "contacts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "phone",
        "message",
        "seen",
    ];

    public function getModePermissions() {
        return [
            "contacts" => [
                "contacts.index",
                "contacts.show",
                "contacts.destroy",
                "contacts.export",
            ],
        ];
    }

    public function scopeSearch($query, $data)
    {
        if( isset($data['name']) && $data['name'] != '' && $data['name'] ) {
            if (filter_var($data['name'], FILTER_VALIDATE_EMAIL) ) { // search by email
                $query = $query->where('email','like','%'.$data['name'].'%');
            } else if (is_numeric($data['name'])) { //search by phone
                $query = $query->where('phone','like','%'.$data['name'].'%');
            } else {
                $query = $query->where('name','like','%'.$data['name'].'%');
            }
        }
        if (isset($data['role']) && $data['role'] > 0) {
            $query = $query->whereHas('roles', function($q) use($data) {
                $q = $q->where('role', $data['role']);
            });
        }
        if (isset($data['seen']) && $data['seen'] != "-1") {
            $query = $query->where('seen',$data['seen']);
        }
        return $query;
    }

    public function showStatus() {
        if($this->seen == 0) {
            return '<span class="badge bg-pill rounded-pill bg-danger-transparent">'.__(ucwords(str_replace("_"," ","UnSeen"))).'</span>';
        } else {
            return '<span class="badge bg-pill rounded-pill bg-green-transparent">'.__(ucwords(str_replace("_"," ","Seen"))).'</span>';
        }
    }

}
