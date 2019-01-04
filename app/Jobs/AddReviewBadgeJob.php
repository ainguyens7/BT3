<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ShopifyApi\ThemesApi;
use App\ShopifyApi\AssetsApi;
use App\Services\WidgetReviewService;

class AddReviewBadgeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    public $timeout = 120;
    public $shopId;
    public $shopDomain;
    public $accessToken;

    protected $themeApi;
    protected $assetApi;
    protected $widgetService;

    const LAYOUT_PRODUCT_TEMPLATE = 'sections/product-template.liquid';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId = '', $shopDomain = '', $accessToken = '')
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $themeApi = app(ThemesApi::class);
        $assetApi = app(AssetsApi::class);
        $widgetService = app(WidgetReviewService::class);
        $themeApi->getAccessToken($this->accessToken, $this->shopDomain);
        $assetApi->getAccessToken($this->accessToken, $this->shopDomain);
        $listTheme = [];
        // get all themes
        $resultTheme = $themeApi->getAllTheme();
        if ($resultTheme['status']) {
            $listTheme = $resultTheme['allTheme'];
        }

        // filter main theme
        $theme = array_filter($listTheme, function($item) {
            return $item->role === 'main';
        });

        if (!empty($theme)) {
            $themeId = array_values($theme)[0]->id;
            $resultLayout = $assetApi->getAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE);

            if ($resultLayout['status']) {
                $assetValue = $resultLayout['assetValue'];
                $value = $assetValue->value;
                $widgetReviewBadge = $widgetService->templateReviewBadge($this->shopId);
                $replacement = '';
                $newValue = str_replace($widgetReviewBadge, $replacement, $value);
                $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE, $newValue);
            }
        }
    }
}
