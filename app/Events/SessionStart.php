<?php

namespace App\Events;

use App\Events\Event;
use App\Models\InternetSession;
use App\Models\Key;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use PhpParser\Node\Expr\Cast\Array_;

class SessionStart extends Event
{
    use SerializesModels;

    public $internetSession;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(InternetSession $internetSession)
    {
        $this->internetSession = $internetSession;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
