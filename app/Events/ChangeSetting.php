<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChangeSetting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $accessToken;
    public $shopDomain;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId, $accessToken, $shopDomain)
    {
        $this->shopId       = $shopId;
        $this->accessToken  = $accessToken;
        $this->shopDomain   = $shopDomain;
    }
}