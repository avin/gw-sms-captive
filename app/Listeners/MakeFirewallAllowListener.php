<?php

namespace App\Listeners;

use App\Events\KeyWasCreated;
use App\Events\SessionStart;
use App\Facades\SmsRu;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MakeFirewallAllowListener
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
    public function makeRule($mac, $ip)
    {
        $process = new Process("sudo /sbin/iptables -t mangle -I internet 1 -m mac --mac-source {$mac} -j RETURN");
        $process->run();

        Log::info("Make firewall rule for mac {$mac}");

    }

    /**
     * Register the listeners for the subscriber.
     * @param KeyWasCreated $event
     * @internal param $events
     */
    public function handle(SessionStart $event)
    {
        $this->makeRule($event->internetSession->mac, $event->internetSession->ip);
    }
}
