<?php
/**
 * Add review box and review badge to product metafield
 */

namespace App\Listeners;

use App\Events\ShopInstall;
use App\Jobs\AddGoogleRating;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\UpdateProductMetafieldJob;
use Exception;

class AddGoogleRatingAssetsListener
{
    /**
     * Handle the event.
     *
     * @param  ShopInstall  $event
     * @return void
     */
    public function handle(ShopInstall $event)
    {
	    dispatch(new AddGoogleRating($event->shopId, $event->shopDomain, $event->accessToken));
    }
}
