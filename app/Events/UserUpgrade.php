<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserUpgrade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $currentPlan;
    public $upgradeTo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $currentPlan = '', $upgradeTo = '')
    {
        $this->shopId = $shopId;
        $this->currentPlan = $currentPlan;
        $this->upgradeTo = $upgradeTo;
    }
}
