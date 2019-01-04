<?php

namespace App\Jobs;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Schema;

class RemoveAreTrial implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 3;

    protected $shopRepo ;
    protected $commentsDefaultRepo ;
    protected $shopId ;
    /**
     * InitDatabaseApps constructor.
     * @param $shopId
     */
    public function __construct($shopId)
    {
	    $this->shopId = $shopId;
    	$this->shopRepo = RepositoryFactory::shopsReposity();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $this->shopRepo->update([
	    	'shop_id' => $this->shopId,
	    	'are_trial' => 0,
	    ]);
    }
}
