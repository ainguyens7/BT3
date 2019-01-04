<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductActionReview
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;

    public $productId;

    public $shopifyDomain;

    public $accessToken;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $productId = '', $shopifyDomain = '', $accessToken = '')
    {
        $this->shopId = $shopId;
        $this->productId = $productId;
        $this->shopifyDomain = $shopifyDomain;
        $this->accessToken = $accessToken;
    }
}
