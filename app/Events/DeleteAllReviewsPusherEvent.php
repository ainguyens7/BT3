<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeleteAllReviewsPusherEvent  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


	public $message;
	public $shopId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shopId,$message)
    {
	    $this->message = $message;
	    $this->shopId = $shopId;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('message-channel-'.$this->shopId);
    }
}
