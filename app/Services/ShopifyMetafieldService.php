<?php

namespace App\Services;

use App\Factory\RepositoryFactory;
use App\Services\UpdateProductMetafieldService;
use Exception;

class ShopifyMetafieldService 
{
    /**
     * Update all shop products metafield
     *
     * @param String $shopId
     * @param String $shopDomain
     * @param String $accessToken
     * @return void
     */
    public static function updateAllShopProductsMetafield($shopId, $shopDomain, $accessToken) {
        $sentry = app('sentry');
        $sentry->user_context([
            'shopDomain' => $shopDomain
        ]);

        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken
        ];

        $productRepo = RepositoryFactory::productsRepository();
        $findProduct = $productRepo->getAllProductSupportWorker($shopId);
        if (!$findProduct['status']) {
            return;
        }

        $products = $findProduct['products']->all();
        
        try {
            UpdateProductMetafieldService::update($products, $data);
        } catch (Exception $ex) {
            $sentry->captureException($ex);
        }
    }
}
