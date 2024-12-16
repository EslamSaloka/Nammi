<?php

namespace App\Support;

use App\Models\Notification as NotificationModel;
use App\Models\User;

class Notification {

    protected $target               = "register";
    protected $targetType           = "";
    protected $to                   = "";
    protected $body                 = "";
    protected $pushNotification     = false;

    public function setTo($to) {
        $this->to = $to;
        return $this;
    }

    public function setTarget($target) {
        $this->target = $target;
        return $this;
    }

    public function setTargetType($targetType) {
        $this->targetType = $targetType;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function setPushNotification($pushNotification) {
        $this->pushNotification = $pushNotification;
        return $this;
    }

    public function build() {
        $n = NotificationModel::create([
            'user_id'   => $this->to,
            'target'    => $this->targetType,
            'target_id' => $this->target,
            'body'      => $this->body,
        ]);
        if($this->pushNotification) {
            $user = $this->getUser();
            if(!is_null($user)) {
                (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody($this->body)->setToken($user->fire_base_token)->build();
            }
        }
        return $n;
    }

    private function getUser() {
        return User::find($this->to);
    }
}
