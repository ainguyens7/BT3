<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;

    public $productId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = null, $productId = null)
    {
        $this->shopId = $shopId;
        $this->productId = $productId;
    }
}
