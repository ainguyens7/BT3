<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShopInstall
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $shopId;
    public $shopDomain;
    public $accessToken;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId, $shopDomain, $accessToken)
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->accessToken = $accessToken;
    }
}
