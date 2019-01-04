<?php

namespace App\Listeners;

use App\Events\LogRecordEvent;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Pusher;

class RealTimeChartListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogRecordEvent  $event
     * @return void
     */
    public function handle(LogRecordEvent $event)
    {
	    $options = array(
		    'cluster' => 'ap1',
		    'encrypted' => true
	    );
	    $pusher = new Pusher(
	    	'cea626639b797197d5ac',
		    'c0cd20e99335e2950412',
		    '444769',
		    $options
	    );
	
//	    $data['message'] = 'hello world';
	    $pusher->trigger('alireviews', 'update_chart', []);
    }
}
