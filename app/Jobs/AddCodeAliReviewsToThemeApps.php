<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\ShopifyApi\AssetsApi;
use App\ShopifyApi\ThemesApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Webmozart\Assert\Assert;

class AddCodeAliReviewsToThemeApps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ThemesApi
     */
    private $_themesApi;

    /**
     * @var AssetsApi
     */
    private $_assetsApi;

    /**
     * @var
     */
    private $_accessToken;

    /**
     * @var
     */
    private $_shopDomain;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * AddCodeAliReviewsToThemeApps constructor.
     * @param $accessToken
     * @param $shopDomain
     */
    public function __construct($accessToken, $shopDomain)
    {
        $this->_accessToken = $accessToken;
        $this->_shopDomain = $shopDomain;
        $this->_themesApi = new ThemesApi();
        $this->_assetsApi = new AssetsApi();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assetFiles = 'templates/product.liquid';
        $aliReviewCode = '<div style="clear: both;"><div id="shopify-ali-review" product-id="{{ product.id }}"> {{ shop.metafields.review_collector.review_code }} </div></div>';
        $currentTheme = $this->getCurrentTheme();
        $assetContent = $this->getAssetsContent($currentTheme, $assetFiles);

        /**
         * Check if Code AliReview Not Exist is add new code
         */
        if( ! $this->checkCodeAliReview($assetContent))
        {
            $newAssetContent = $assetContent.$aliReviewCode;
            $this->_assetsApi->getAccessToken($this->_accessToken, $this->_shopDomain);
            $addCodeAliReview = $this->_assetsApi->updateAssetValue($currentTheme, $assetFiles, $newAssetContent);
            if( ! $addCodeAliReview['status'])
            {
                Helpers::saveLog('error', ['message' => 'Add Code AliReview Form to Front-end error', 'domain' => $this->_shopDomain]);
            }
        }
    }

    /**
     * @return bool
     */
    private function getCurrentTheme()
    {
        $this->_themesApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $allTheme = $this->_themesApi->getAllTheme();
        if($allTheme['status'])
        {
            $allTheme = $allTheme['allTheme'];
            foreach ($allTheme as $k => $v)
            {
                if($v->role === 'main')
                    return $v->id;
            }
        }
        return false;
    }

    /**
     * @param $currentTheme
     * @param $assetFile
     * @return bool
     */
    private function getAssetsContent($currentTheme, $assetFile)
    {
        $this->_assetsApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $assetValue = $this->_assetsApi->getAssetValue($currentTheme, $assetFile);
        if($assetValue['status'])
        {
            return $assetValue['assetValue']->value;
        }

        return false;
    }

    /**
     * @param $assetContent
     * @return bool
     */
    private function checkCodeAliReview($assetContent)
    {
        $pattern = '/<div\s*id=(\"|\')shopify-ali-review(\"|\')\s*product-id=(\"|\')\{\{\s*product.id\s*\}\}(\"|\')>\s*\{\{\s*shop.metafields.review_collector.review_code\s*\}\}\s*<\/div>/';
        preg_match($pattern, $assetContent, $matches, PREG_OFFSET_CAPTURE);
        if(empty($matches))
            return false;
        else
            return true;
    }


}
