<?php

namespace App\Listeners;

use App\Jobs\UpdateDatabase;

class UpdateDatabaseSubscriber
{
    /**
     * Inject asset
     */
    public function updateDatabase($event)
    {
        $shopId = $event->shopId;
        $shopDomain = $event->shopDomain;
        $accessToken = $event->accessToken;

        dispatch(new UpdateDatabase($shopId));
    }

    /**
     * Register multi listeners
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\UpdateDatabaseSubscriber@updateDatabase'
        );
    }
}