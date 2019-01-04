<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BeforeImportReviewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $value;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId, $value = null)
    {
        $this->shopId = $shopId;
        $this->value = $value;
    }
}
