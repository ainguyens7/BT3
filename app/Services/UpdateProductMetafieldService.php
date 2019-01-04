<?php

/**
 * Centralize processing update product
 * Add review box into product metafield
 * Add review badge into product metafield
 */

namespace App\Services;

use App\Helpers\Helpers;
use App\Facades\WorkerApi;

class UpdateProductMetafieldService
{
    public static function update($productIdList, $data)
    {
        $payload = array_merge($data,[
            'product_ids' => $productIdList,
            'metafield' => [
                [
                    'type' => 'review_box',
                    'namespace' => config('widgetreview.widget_namespace'),
                    'metafield_key' => config('widgetreview.widget_review_key')
                ],
                [
                    'type' => 'review_badge',
                    'namespace' => config('widgetreview.badge_namespace'),
                    'metafield_key' => config('widgetreview.badge_review_key'),
                ]
            ]
        ]);
        // add product metafield review box
        self::addProductMetafield($payload);
    }

    protected static function addProductMetafield($data)
    {
        return WorkerApi::callApi('add_product_metafield', $data);
    }

    public static function delete($productId, $data)
    {
        $payload = array_merge($data, [
            'product_id' => $productId,
            'namespace' => config('widgetreview.widget_namespace')
        ]);
        self::deleteProductMetafield($payload);
    }

    protected static function deleteProductMetafield($data)
    {
        return WorkerApi::callApi('delete_product_metafield', $data);
    }
}