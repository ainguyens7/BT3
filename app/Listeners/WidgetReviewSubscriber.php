<?php

namespace App\Listeners;

use App\Facades\WorkerApi;
use Illuminate\Support\Facades\URL;
use App\Services\WidgetReviewService;
use App\Jobs\AddReviewBoxJob;
use App\Jobs\AddReviewBadgeJob;
use App\Jobs\AddReviewBadgeCollectionJob;

class WidgetReviewSubscriber
{
    protected $widgetReviewService;

    public function __construct()
    {
        $this->widgetReviewService = new WidgetReviewService();
    }
    /**
     * Inject asset
     */
    public function injectReviewBox($event)
    {
        $shopId = $event->shopId;
        $shopDomain = $event->shopDomain;
        $accessToken = $event->accessToken;

        dispatch(new AddReviewBoxJob($shopId, $shopDomain, $accessToken));
    }


    public function injectReviewBadget($event)
    {
        // $shopId = $event->shopId;
        // $shopDomain = $event->shopDomain;
        // $accessToken = $event->accessToken;

        // dispatch(new AddReviewBadgeJob($shopId, $shopDomain, $accessToken));
    }

    public function injectReviewBadgeCollection($event)
    {
        // $shopId = $event->shopId;
        // $shopDomain = $event->shopDomain;
        // $accessToken = $event->accessToken;

        // dispatch(new AddReviewBadgeCollectionJob($shopId, $shopDomain, $accessToken));
    }

    /**
     * Register multi listeners
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ShopInstall',
            'App\Listeners\WidgetReviewSubscriber@injectReviewBox'
        );
        $events->listen(
            'App\Events\ShopInstall',
            'App\Listeners\WidgetReviewSubscriber@injectReviewBadget'
        );
        // $events->listen(
        //     'App\Events\ShopInstall',
        //     'App\Listeners\WidgetReviewSubscriber@injectReviewBadgeCollection'
        // );

        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\WidgetReviewSubscriber@injectReviewBox'
        );
        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\WidgetReviewSubscriber@injectReviewBadget'
        );
    }
}