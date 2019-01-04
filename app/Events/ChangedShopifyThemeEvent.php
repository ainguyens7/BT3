<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChangedShopifyThemeEvent
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
