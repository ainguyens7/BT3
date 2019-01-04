<?php

namespace App\Listeners;

use App\Events\SaveIntercomEvent;
use App\Jobs\EventIntercom;
use App\Services\IntercomService;
use Illuminate\Support\Facades\Redis;

class SaveIntercomEventListeners
{

	public function __construct()
	{
		//

		$this->redis = Redis::connection('redis_cache_frontend');
	}

	/**
	 * Handle the event.
	 *
	 * @param  SaveIntercomEvent $event
	 *
	 * @return void
	 */
	public function handle(SaveIntercomEvent $event)
	{
		$shopID = $event->shopId;
		$type = $event->type;

		dispatch(new EventIntercom($shopID,$type));
	}
}
