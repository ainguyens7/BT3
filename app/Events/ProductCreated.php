<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $shopId;
    
    public $productId;

    public $shopDomain;

    public $token;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $shopDomain = '', $productId = '', $token = '')
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->productId = $productId;
        $this->token = $token;
    }
}
