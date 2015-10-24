<?php

namespace App\Listeners;

use App\Events\KeyWasCreated;
use App\Facades\SmsRu;
use Illuminate\Support\Facades\Log;

class SendCodeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  KeyWasCreated  $event
     * @return void
     */
    public function sendSmsCode($code, $number)
    {
        $number  = '7' . $number;

        Log::info("Send code '{$code}' to '{$number}'");

        SmsRu::sendSms($number, "Your code is {$code}");
    }

    /**
     * Register the listeners for the subscriber.
     * @param KeyWasCreated $event
     * @internal param $events
     */
    public function handle(KeyWasCreated $event)
    {
        $this->sendSmsCode($event->key->key, $event->key->phone_number);
    }
}
