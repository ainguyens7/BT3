<?php

/**
 * Centralize processing update product
 * Add review box into product metafield
 * Add review badge into product metafield
 */

namespace App\Services;

use App\Helpers\Helpers;
use Exception;

class ProductUpdate
{
    protected $sentry;
    
    public static function run($shopId, $productIdList)
    {
        $data = [
            'shop_id' => $shopId,
            'product_ids' => $productIdList
        ];
        // add product metafield review box
        self::addMetafieldReviewBox(array_merge($data, [
            'namespace' => config('widgetreview.widget_namespace'),
            'metafield_key' => config('widgetreview.widget_review_key')
        ]));
        // add product metafield review badge
        self::addMetafieldReviewBadge(array_merge($data, [
            'namespace' => config('widgetreview.badge_namespace'),
            'metafield_key' => config('widgetreview.badge_review_key'),
        ]));
    }

    protected static function addMetafieldReviewBox($data)
    {
        return WorkerApi::callApi('add_widget_review_box', $data);
    }

    protected static function addMetafieldReviewBadge($data)
    {
        $result = WorkerApi::callApi('update_review_badge', $data);
    }
}