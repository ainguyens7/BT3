<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImportedProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $shopId;
    public $accessToken;
    public $shopDomain;
    public $productIdList;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $accessToken = '', $shopDomain = '', $productIdList = [])
    {
        $this->shopId = $shopId;
        $this->accessToken = $accessToken;
        $this->shopDomain = $shopDomain;
        $this->productIdList = $productIdList;
    }
}
