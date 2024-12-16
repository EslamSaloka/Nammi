<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'target',
        'target_id',
        'body',
        'seen',
    ];

    public function getModePermissions() {
        return [
            "notifications" => [
                "notifications.index",
                "notifications.show",
                "notifications.create",
                "notifications.destroy",
            ],
        ];
    }

    public function showSeen() {
        if($this->seen == 0) {
            return '<span class="make_pad badge bg-danger">'.__("لم يتم المشاهدة").'</span>';
        } else {
            return '<span class="make_pad badge bg-success">'.__("تم المشاهده").'</span>';
        }
    }

    public function targetTypes() {
        return [
            "register",
            "edit_profile",
            "change_password",
            // ========================== //
            "create_new_order",
            "accepted_order",
            "rejected_order",
            "time_change_order",
            "confirmed_order",
            "completed_order",
            // ========================== //
        ];
    }

    public function getWebNotification() {
        if($this->target == "register") {
            $route = route("admin.profile.index");
        } else if($this->target == "edit_profile") {
            $route = route("admin.profile.index");
        } else if($this->target == "change_password") {
            $route = route("admin.change_password.index");
        } else if($this->target == "accept_club") {
            $route = route("admin.clubs.edit",$this->user_id);
        } else if($this->target == "reject_club") {
            $route = route("admin.clubs.edit",$this->user_id);
        } else if($this->target == "due_completed") {
            $route = route("admin.dues.index")."?due_id={$this->target_id}";
        } else if($this->target == "notification") {
            $route = route("admin.notifications.index");
        }
        return [
            "title"  => $this->body,
            "route"  => $route ?? '',
            "nRoute" => route("admin.notifications.show",$this->id),
        ];
    }
}
