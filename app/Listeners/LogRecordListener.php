<?php

namespace App\Listeners;

use App\Events\LogRecordEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repository\LogRepository;
class LogRecordListener
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
	 * @param  LogRecordEvent $event
	 *
	 * @return void
	 */
	public function handle(LogRecordEvent $event)
	{
		$shopID = $event->shopID;
		$type = $event->type;
		$value = $event->value;
		$arg = [
			'shop_id' => $shopID,
			'type' => $type,
			'value' => $value
		];
		$logRepo = app(LogRepository::class);
		$logRepo->save($arg);
	}
}
