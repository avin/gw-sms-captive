<?php namespace App\Services;

class SmsRu {

    protected $client;

    /**
     * IcmpPing constructor.
     */
    public function __construct($smsRuKey)
    {
        $this->client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($smsRuKey));
    }

    public function sendSms($phone, $content, $translit=false){
        $sms = new \Zelenin\SmsRu\Entity\Sms($phone, $content);
        return $this->client->smsSend($sms);
    }

    public function getBalance(){
        return $this->client->myBalance()->balance;
    }
}