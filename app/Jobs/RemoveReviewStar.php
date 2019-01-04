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

class RemoveReviewStar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_themesApi;

    private $_assetsApi;

    private $_accessToken;

    private $_shopDomain;

    private $_shopId;

    public $tries = 3;

    public function __construct($accessToken, $shopDomain, $shopId)
    {
        $this->_accessToken = $accessToken;
        $this->_shopDomain = $shopDomain;
        $this->_shopId = $shopId;
        $this->_themesApi = new ThemesApi();
        $this->_assetsApi = new AssetsApi();
    }

    public function handle()
    {
        $assetFiles = 'sections/product-template.liquid';
        $patternRemoveTemplate = '<div id="alireview-review-widget-badge" data-shop-id="'.$this->_shopId.'" product-id="{{ product.id }}"> {{ product.metafields.alireviews.badge_review_key }} </div>'; 
        $currentTheme = $this->getCurrentTheme();
        $assetContent = $this->getAssetsContent($currentTheme, $assetFiles);
        if ($this->checkAliReviewCode($assetContent, $patternRemoveTemplate))
        {
            $assetContent = str_replace($patternRemoveTemplate, '', $assetContent);
            $this->_assetsApi->getAccessToken($this->_accessToken, $this->_shopDomain);
            $addCodeAliReview = $this->_assetsApi->updateAssetValue($currentTheme, $assetFiles, $assetContent);
            if (!$addCodeAliReview['status'])
            {
                Helpers::saveLog('error', ['message' => 'Delete Code AliReview 3.3.1 Form to Front-end error', 'domain' => $this->_shopDomain]);
            }
        }
    }

    private function getCurrentTheme()
    {
        $this->_themesApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $allTheme = $this->_themesApi->getAllTheme();
        if ($allTheme['status']) {
            $allTheme = $allTheme['allTheme'];
            foreach ($allTheme as $k => $v) {
                if($v->role === 'main')
                    return $v->id;
            }
        }
        return false;
    }

    private function getAssetsContent($currentTheme, $assetFile)
    {
        $this->_assetsApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $assetValue = $this->_assetsApi->getAssetValue($currentTheme, $assetFile);
        if ($assetValue['status']) {
            return $assetValue['assetValue']->value;
        }
        return false;
    }

    private function checkAliReviewCode($assetContent, $patternRemoveTemplate)
    {
        return !strpos($assetContent, $patternRemoveTemplate) ? false : true;
    }

}