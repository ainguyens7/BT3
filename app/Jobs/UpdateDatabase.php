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

class UpdateDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 3;

    protected $commentRepo ;
    protected $commentsDefaultRepo ;
    protected $productRepo;
    protected $shopId ;
    /**
     * InitDatabaseApps constructor.
     * @param $shopId
     */
    public function __construct($shopId)
    {
	    $this->shopId = $shopId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commentRepo = RepositoryFactory::commentBackEndRepository();
        $productRepo = RepositoryFactory::productsRepository();
	    Helpers::saveLog('error', ['message' => 'update database run']);

	    $updateColumnPin  = $commentRepo->addPinColumn($this->shopId);
    	$updateColumnLike  = $commentRepo->addLikeColumn($this->shopId);
        $updateColumnUnlike  = $commentRepo->addUnLikeColumn($this->shopId);
        // added in v3.3
        $addColumnAliexpressLink = $productRepo->addColumn($this->shopId, 'review_link');
    }
}
