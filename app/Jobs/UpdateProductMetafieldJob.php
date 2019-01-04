<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ShopifyApi\MetaFieldApi;
use App\Services\ReviewService;

class UpdateProductMetafieldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    public $timeout = 120;
    public $shopId;
    public $shopDomain;
    public $accessToken;
    public $productId;
    protected $metafieldApi;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $shopDomain = '', $accessToken = '', $productId = '')
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->accessToken = $accessToken;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $metafieldApi = app(MetaFieldApi::class);
        // $metafieldApi->getAccessToken($this->accessToken, $this->shopDomain);
        // $reviewService = new ReviewService($this->shopId, $this->productId);
        // $reviewBox = $reviewService->generateReviewBox();
        // $reviewBadge = $reviewService->generateBadgeReview();
        // $boxValue = $reviewBox['status'] ? $reviewBox['result'] : '<div></div>';
        // $badgeValue = $reviewBadge['status'] ? $reviewBadge['result'] : '<div></div>';

        // // create review box metafield
        // $resultReviewBox = $metafieldApi->addProductMetafield($this->productId, config('widgetreview.widget_namespace'), config('widgetreview.widget_review_key'), $boxValue);

        // $resultReviewBadge = $metafieldApi->addProductMetafield($this->productId, config('widgetreview.badge_namespace'), config('widgetreview.badge_review_key'), $badgeValue);
    }
}