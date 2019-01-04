<?php
/**
 * Add review box and review badge to product metafield
 */

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\UpdateProductMetafieldJob;
use Exception;

class AddReviewWidget
{
    /**
     * Handle the event.
     *
     * @param  ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        // $shopDomain = $event->shopDomain;
        // $shopId = $event->shopId;
        // $productId = $event->productId;
        // $token = $event->token;
        // dispatch(new UpdateProductMetafieldJob($shopId, $shopDomain, $token, $productId));
    }
}
