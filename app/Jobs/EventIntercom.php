<?php

namespace App\Jobs;

use App\Services\IntercomService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intercom\IntercomEvents;

class EventIntercom implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

	/**
	 * The number of times job may be attempted
	 */
	public $tries = 3;

	/**
	 * Timeout of job
	 */
	public $timeout = 1200;

    public $shopId;
    public $type;

    public function __construct($shopId,$type)
    {
    	$this->shopId = $shopId;
    	$this->type = $type;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//	    echo ($this->type .' - '.$this->shopId);

	    $intercomService = new IntercomService($this->shopId);
	    $create = $intercomService->createEvent($this->type);
    }
}
