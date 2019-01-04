<?php

namespace App\Jobs;

use App\Events\UpdateCache;
use App\Repository\CommentBackEndRepository;
use App\Repository\ProductsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class UpdateProductAfterDeleteReviewsOfShop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * The number of times job may be attempted
	 */
	public $tries = 3;

	/**
	 * Timeout of job
	 */
	public $timeout = 1200;

	private $shopId;
	private $commentBackendRepo;
	private $productRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId)
    {
        //
	    $this->shopId = $shopId;
	    $this->commentBackendRepo = new CommentBackEndRepository();
	    $this->productRepo = new ProductsRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $this->productRepo->updateIsReviewsAllProducts($this->shopId);
    }
}
