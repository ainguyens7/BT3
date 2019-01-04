<?php

namespace App\Services;

class WidgetReviewService
{
    public function templateReviewBox($shopId)
    {
        $namespaceReviewBox = config('widgetreview.widget_namespace');
        $keyReviewBox = config('widgetreview.widget_review_key');
        return "<div id=\"shopify-ali-review\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"><div class=\"shop_info\" shop-id=\"{$shopId}\" style=\"display: none\"></div> <div class=\"reviews\">{{ product.metafields.{$namespaceReviewBox}.{$keyReviewBox} }}</div> </div>";
    }

    public function templateReviewBadgeCollection($shopId)
    {
        $namespace = config('widgetreview.badge_namespace');
        $key = config('widgetreview.badge_review_key');
        return "<a href=\"{{product.url}}#shopify-ali-review\"><div id=\"alireview-review-widget-badge\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespace}.{$key} }} </div></a>";
    }

    public function templateReviewBadge($shopId)
    {
        $namespace = config('widgetreview.badge_namespace');
        $key = config('widgetreview.badge_review_key');
        return "<div id=\"alireview-review-widget-badge\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespace}.{$key} }} </div>";
    }
}