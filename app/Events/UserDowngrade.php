<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserDowngrade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $currentPlan;
    public $downgradeTo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $currentPlan = '', $downgradeTo = '')
    {
        $this->shopId = $shopId;
        $this->currentPlan = $currentPlan;
        $this->downgradeTo = $downgradeTo;
    }
}
