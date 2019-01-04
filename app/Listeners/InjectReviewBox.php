<?php

namespace App\Listeners;

use App\Events\UpdateApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\WorkerApi;

class InjectReviewBox
{

    protected $sentry;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sentry = app('sentry');
    }

    /**
     * Handle the event.
     *
     * @param  UpdateApp  $event
     * @return void
     */
    public function handle(UpdateApp $event)
    {
        $shopId = $event->shopId;
        $accessToken = $event->accessToken;
        $shopDomain = $event->shopDomain;

        $this->sentry->user_context([
            'shopDomain' => $shopDomain
        ]);

        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken,
            'review_box' => $this->templateReviewBox($shopId)
        ];
        try {
            WorkerApi::callApi('inject_review_box', $data);
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
        }
    }

    /**
     * Duplicated with @getReviewBoxTemplate
     * At App\Services\ShopifyThemeService:64
     */
    public function templateReviewBox($shopId)
    {
        $namespaceReviewBox = config('widgetreview.widget_namespace');
        $keyReviewBox = config('widgetreview.widget_review_key');
        return "<div id=\"review-widget-box\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespaceReviewBox}.{$keyReviewBox} }} </div>";
    }
}
