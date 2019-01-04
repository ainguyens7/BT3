<?php

namespace App\Listeners;

use App\Events\ShopInstall;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\WebHookApps;

class RegisterWebhook
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
        $accessToken = $event->accessToken;
        $shopDomain = $event->shopDomain;

        dispatch(new WebHookApps($accessToken, $shopDomain));
    }
}
