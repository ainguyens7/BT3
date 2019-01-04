<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ShopifyApi\AssetsApi;
use App\ShopifyApi\ThemesApi;
use App\Services\AssetThemeService;

class AddAssetCoreFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shopId;
    public $shopDomain;
    public $accessToken;
    protected $themeApi;
    protected $assetApi;
    protected $assetThemeService;

    const ASSET_CORE_FILE = 'snippets/alireviews_core.liquid';

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
        $assetThemeService = app(AssetThemeService::class);
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
            $value = $assetThemeService->getAlireviewAssetCore($this->shopId);

            $resultLayout = $assetApi->updateAssetValue($themeId, self::ASSET_CORE_FILE, $value);
        }
    }
}
