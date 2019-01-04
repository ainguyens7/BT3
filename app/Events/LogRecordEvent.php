<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LogRecordEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopID;
    public $type;
    public $value;
    public function __construct($shopID, $type, $value = null)
    {
        $this->shopID = $shopID;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
