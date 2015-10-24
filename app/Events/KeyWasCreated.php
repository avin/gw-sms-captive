<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Key;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use PhpParser\Node\Expr\Cast\Array_;

class KeyWasCreated extends Event
{
    use SerializesModels;

    public $key;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
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
