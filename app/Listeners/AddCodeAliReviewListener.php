<?php

namespace App\Listeners;

use App\Events\ShopInstall;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\AddCodeAliReviewsToThemeApps;
use App\Jobs\MetaFieldApps;

class AddCodeAliReviewListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShopInstall  $event
     * @return void
     */
    public function handle(ShopInstall $event)
    {
        dispatch(new AddCodeAliReviewsToThemeApps($event->accessToken, $event->shopDomain));
        dispatch(new MetaFieldApps($event->accessToken, $event->shopDomain, $event->shopId));
    }
}
