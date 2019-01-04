<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopDomain;

    public $productId;

    public $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopDomain = '', $productId = '', $token = '')
    {
        $this->shopDomain = $shopDomain;
        $this->productId = $productId;
        $this->token = $token;
    }
}
