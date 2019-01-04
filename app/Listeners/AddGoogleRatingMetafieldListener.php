<?php
/**
 * Add review box and review badge to product metafield
 */

namespace App\Listeners;

use App\Events\AddGoogleRatingEvent;
use App\Jobs\AddSEORatingJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\UpdateProductMetafieldJob;
use Exception;

class AddGoogleRatingMetafieldListener
{
    /**
     * Handle the event.
     *
     * @param  AddGoogleRatingEvent  $event
     * @return void
     */
    public function handle(AddGoogleRatingEvent $event)
    {
	    dispatch(new AddSEORatingJob($event->shopId, $event->shopDomain, $event->accessToken, $event->productId));
    }
}
