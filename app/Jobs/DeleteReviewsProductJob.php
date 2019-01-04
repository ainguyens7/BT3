<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Factory\RepositoryFactory;

class DeleteReviewsProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 0;
    public $timtoue = 120;

    protected $shopId;
    protected $productId;
    protected $reviewRepo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId, $productId)
    {
        $this->shopId = $shopId;
        $this->productId = $productId;
        $this->reviewRepo = RepositoryFactory::commentBackEndRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sentry = app('sentry');
        $sentry->user_context([
            'shopId' => $this->shopId,
            'productId' => $this->productId
        ]);
        try {
            // silence check result
            $this->reviewRepo->delReviewByProductList($this->shopId, $this->productId);
        } catch (Exception $ex) {
            $sentry->captureException($ex);
        }
    }

    public function failed(Exception $ex)
    {
        $sentry = app('sentry');
        $sentry->captureException($ex);
    }
}