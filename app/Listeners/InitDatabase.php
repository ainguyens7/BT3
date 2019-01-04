<?php

namespace App\Listeners;

use App\Events\ShopInstall;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\InitDatabaseApps;

class InitDatabase
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
        $shopId = $event->shopId;
        dispatch(new InitDatabaseApps($shopId));
    }
}
