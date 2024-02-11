<?php

namespace App\Services;
use Twilio\Rest\Client;

class NotificationService
{

    private $accountSid;
    private $token;
    private $phone;

    public function __construct()
    {
        $this->accountSid = env('TWILIO_ACCOUNT_SID');
        $this->token = env('TWILIO_AUTH_TOKEN');
        $this->phone = env('TWILIO_PHONE_NUMBER');
    }

    public function client() {
        return new Client($this->accountSid, $this->token);
    }

    public function send(string $to, string $message) {
        return $this->client()->messages->create($to,
                    [
                        "body" => $message,
                        "from" => $this->phone
                    ]
                );
    }
}
