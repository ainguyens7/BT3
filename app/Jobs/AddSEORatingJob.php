<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ShopifyApi\MetaFieldApi;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Cache;

class AddSEORatingJob implements ShouldQueue
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
	    if (!Cache::has('schemaGoogleAdded_'.$this->shopId) or empty(Cache::get('schemaGoogleAdded_'.$this->shopId))) {
			dispatch( new AddGoogleRating($this->shopId,$this->shopDomain,$this->accessToken));
	    }

	    $metafieldApi = app(MetaFieldApi::class);
        $metafieldApi->getAccessToken($this->accessToken, $this->shopDomain);
        $reviewService = new ReviewService($this->shopId, $this->productId);
        $reviewBadge = $reviewService->generateBadgeReview();
        $badgeValue = $reviewBadge['status'] ? $reviewBadge['result'] : '<div></div>';
        $badgeValueScript = $reviewBadge['status'] ? $reviewBadge['resultScript'] : '';

        $metafieldApi->addProductMetafield($this->productId, config('widgetreview.widget_namespace'), config('widgetreview.seo_rating_review_key'), $badgeValue);
        $metafieldApi->addProductMetafield($this->productId, config('widgetreview.widget_namespace'), config('widgetreview.seo_rating_review_key_script'), $badgeValueScript);
    }
}