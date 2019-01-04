<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChangedThemeSettingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $shopDomain;
    public $accessToken;
    public $success;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId)
    {
        $this->shopId = $shopId;
        $this->shopDomain = session('shopDomain');
        $this->accessToken = session('accessToken');
    }
}
