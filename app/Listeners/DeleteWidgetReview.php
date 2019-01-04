<?php

namespace App\Listeners;

use App\Events\ProductDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\UpdateProductMetafieldService;

class DeleteWidgetReview
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
     * @param  ProductDeleted  $event
     * @return void
     */
    public function handle(ProductDeleted $event)
    {
        $shopDomain = $event->shopDomain;
        $productId = $event->productId;
        $token = $event->token;

        $data = [
            'shop_domain' => $shopDomain,
            'access_token' => $token
        ];
        $this->sentry->user_context([
            'shop_domain' => $shopDomain,
            'product_id' => $productId
        ]);
        try {
            UpdateProductMetafieldService::delete($productId, $data);
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
        }
    }
}
