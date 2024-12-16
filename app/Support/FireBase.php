<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class FireBase {

    protected $serverToken  = ""; // Fire Base Server Key
    protected $serverUrl    = "https://fcm.googleapis.com/fcm/send"; // Fire Base URL
    protected $title        = "";
    protected $body         = "";
    protected $token        = [];
    protected $by           = "system";
    protected $message_to   = "all";

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function setToken($token) { 
        $this->token = (is_array($token)) ? $token : [$token];
        return $this;
    }
    
    private function getHeaders() {
        return [
            "Content-Type: application/json",
            "Authorization: key={$this->serverToken}",
        ];
    }

    private function getData() {
        return [
            'title'         => $this->title,
            'body'          => $this->body,
            'type'          => $this->by,
            'tickerText'    => '',
            'vibrate'       => 1,
            'sound'         => 1,
            'largeIcon'     => 'large_icon',
            'smallIcon'     => 'small_icon',
        ];
    }

    private function getFields() {
        if($this->message_to == "all") {
            $fields = [
                'notification' => $this->getData(),
                'to'    => '/topics/all'
            ];
        } else {
            $fields = [
                'registration_ids' => $this->token,
                'notification' => $this->getData()
            ];
        }
        return json_encode($fields);
    }

    public function build() {
        return Http::withHeaders($this->getHeaders())->post($this->serverUrl, $this->getFields())->json();
    }

}