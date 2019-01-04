<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdatedReviewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;
    public $productId;
    public $commentData;
    public $shopifyDomain;
    public $accessToken;

    /**
     * Create a new event instance.
     *
     * @param  String  $shopId
     * @param  String  $productId
     * @param  String|CommentsModel  $commentData
     * @return void
     */
    public function __construct($shopId, $productId, $commentData = null)
    {
        $this->shopId = $shopId;
        $this->productId = $productId;
        $this->commentData = $commentData;
        $this->shopifyDomain = session('shopDomain');
        $this->accessToken = session('accessToken');
    }
}
