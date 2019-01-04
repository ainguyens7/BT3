<?php

namespace App\Listeners;

use App\Events\UpdateApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\WorkerApi;

class InjectReviewBadge
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
        $accessToken = $event->accessToken;
        $shopDomain = $event->shopDomain;
        $shopId = $event->shopId;

        $this->sentry->user_context([
            'shopDomain' => $shopDomain
        ]);

        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken,
            'review_badge' => $this->templateReviewBadge($shopId)
        ];
        try {
            WorkerApi::callApi('inject_review_badge', $data);
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
        }
    }

    /**
     * Duplicated with @getReviewBoxTemplate
     * At App\Services\ShopifyThemeService:81
     */
    public function templateReviewBadge($shopId)
    {
        $namespace = config('widgetreview.badge_namespace');
        $key = config('widgetreview.badge_review_key');
        return "<div id=\"review-widget-badge\" data-shop-id=\"{$shopId}\" product-id=\"{{ product.id }}\"> {{ product.metafields.{$namespace}.{$key} }} </div>";
    }
}
