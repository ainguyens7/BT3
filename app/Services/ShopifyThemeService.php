<?php

namespace App\Services;

use App\Facades\WorkerApi;
use Exception;

class ShopifyThemeService 
{
    /**
     * Update review box widget template
     *
     * @param String $shopId
     * @param String $shopDomain
     * @param String $accessToken
     * @return void
     */
    public static function updateReviewBoxTemplate($shopId, $shopDomain, $accessToken) {
        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken,
            'review_box' => self::getReviewBoxTemplate($shopId)
        ];

        try {
            WorkerApi::callApi('inject_review_box', $data);
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
    }

    /**
     * Update review badge widget template
     *
     * @param String $shopId
     * @param String $shopDomain
     * @param String $accessToken
     * @return void
     */
    public static function updateReviewBadgeTemplate($shopId, $shopDomain, $accessToken) {
        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken,
            'review_badge' => self::getReviewBadgeTemplate($shopId)
        ];

        try {
            WorkerApi::callApi('inject_review_badge', $data);
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
    }

    /**
     * Duplicated with @templateReviewBox
     * At App\Listeners\InjectReviewBox:53
     */

    /**
     * Generate review box template
     * @param  String $shopId
     * @return String
     */
    public static function getReviewBoxTemplate($shopId)
    {
        $namespaceReviewBox = config('widgetreview.widget_namespace');
        $keyReviewBox = config('widgetreview.widget_review_key');
        return "<div id=\"review-widget-box\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespaceReviewBox}.{$keyReviewBox} }} </div>";
    }

    /**
     * Duplicated with @templateReviewBadge
     * At App\Listeners\InjectReviewBadge:51
     */

    /**
     * Generate review badge template
     * @param  String $shopId
     * @return String
     */
    public static function getReviewBadgeTemplate($shopId)
    {
        $namespace = config('widgetreview.badge_namespace');
        $key = config('widgetreview.badge_review_key');
        return "<div id=\"review-widget-badge\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespace}.{$key} }} </div>";
    }
}
