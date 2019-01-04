<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\ShopifyApi\ThemesApi;
use App\ShopifyApi\AssetsApi;

class InjectAssetCoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    public $timeout = 120;
    public $shopId;
    public $shopDomain;
    public $accessToken;

    protected $themeApi;
    protected $assetApi;

    const LAYOUT_THEME = 'layout/theme.liquid';
    const ASSET_CORE = "{% include 'alireviews_core' %}";
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
            $resultLayout = $assetApi->getAssetValue($themeId, self::LAYOUT_THEME);

            if ($resultLayout['status']) {
                $assetValue = $resultLayout['assetValue'];
                $value = $assetValue->value;

                if (!strpos($value, self::ASSET_CORE)) {
                    $assetCore = self::ASSET_CORE;
                    $newValue = "{$assetCore} \n </head>";
                    $pattern = '/<\/head>/';
                    $replace = preg_replace($pattern, $newValue, $value);
                    $assetApi->updateAssetValue($themeId, self::LAYOUT_THEME, $replace);
                }
            }
        }
    }
}
