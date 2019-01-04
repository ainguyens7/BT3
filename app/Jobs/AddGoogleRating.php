<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ShopifyApi\ThemesApi;
use App\ShopifyApi\AssetsApi;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Cache;


class AddGoogleRating implements ShouldQueue
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

    const LAYOUT_PRODUCT_TEMPLATE_SECTION = 'sections/product-template.liquid';
    const LAYOUT_PRODUCT_TEMPLATE = 'templates/product.liquid';
    const LAYOUT_PRODUCT_TEMPLATE_SNIPPETS = 'snippets/product-template.liquid';
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
	 * @return bool
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
	        $metafield = $this->getMetafield();
	        $metafieldScript = $this->getMetafieldScript();

	        $pattern =  '/<div\s+.*itemtype=[\'"]http:\/\/schema\.org\/Product*[\'"](.*?)>/s';
	        $patternScript = '/[\'"]@context[\'"].*http:\/\/schema\.org.*,/m';

	        $resultLayoutProducSection = $assetApi->getAssetValue($themeId,self::LAYOUT_PRODUCT_TEMPLATE_SECTION);
	        if($resultLayoutProducSection['status']){
		        $assetValue = $resultLayoutProducSection['assetValue'];
		        $value = $assetValue->value;

		        if (!strpos($value, $metafield) && !strpos($value, $metafieldScript)) {
			        $replacement = '${0} '."\n {$metafield}";
			        $newValue = preg_replace($pattern, $replacement, $value);
			        if (strpos($newValue, $metafield)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE_SECTION, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }

			        $replacement = '${0} '."\n {$metafieldScript}";
			        $newValue = preg_replace($patternScript, $replacement, $value);
			        if (strpos($newValue, $metafieldScript)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE_SECTION, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }
		        }else{
			        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//			        return TRUE;
		        }
	        }

	        // if templates/product.liquid not found or not have code schema
	        $resultLayoutProduct = $assetApi->getAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE);
	        if($resultLayoutProduct['status']){
		        $assetValue = $resultLayoutProduct['assetValue'];
		        $value = $assetValue->value;

		        if (!strpos($value, $metafield) && !strpos($value, $metafieldScript)) {
			        $replacement = '${0} '."\n {$metafieldScript}";
			        $newValue = preg_replace($patternScript, $replacement, $value);
			        if (strpos($newValue, $metafieldScript)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }

			        $replacement = '${0} '."\n {$metafield}";
			        $newValue = preg_replace($pattern, $replacement, $value);
			        if (strpos($newValue, $metafield)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }
		        }else{
			        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//			        return TRUE;
		        }
	        }


	        $resultLayoutProductSnippets = $assetApi->getAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE_SNIPPETS);
	        if($resultLayoutProductSnippets['status']){
		        $assetValue = $resultLayoutProductSnippets['assetValue'];
		        $value = $assetValue->value;

		        if (!strpos($value, $metafield) && !strpos($value, $metafieldScript)) {
			        $replacement = '${0} '."\n {$metafield}";
			        $newValue = preg_replace($pattern, $replacement, $value);
			        if (strpos($newValue, $metafield)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE_SNIPPETS, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }

			        $replacement = '${0} '."\n {$metafieldScript}";
			        $newValue = preg_replace($patternScript, $replacement, $value);
			        if (strpos($newValue, $metafieldScript)) {
				        $assetApi->updateAssetValue($themeId, self::LAYOUT_PRODUCT_TEMPLATE_SNIPPETS, $newValue);
				        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//				        return TRUE;
			        }
		        }else{
			        Cache::forever('schemaGoogleAdded_'.$this->shopId, true);
//			        return TRUE;
		        }
	        }
        }

        return FALSE;
    }

    public function getMetafield()
    {
        $namespace = config('widgetreview.widget_namespace');
        $key = config('widgetreview.seo_rating_review_key');
        return "<div> {{ product.metafields.{$namespace}.{$key} }} </div>";
    }

	public function getMetafieldScript()
	{
		$namespace = config('widgetreview.widget_namespace');
		$key = config('widgetreview.seo_rating_review_key_script');
		return "{{ product.metafields.{$namespace}.{$key} }}";
	}

}
