<?php

namespace App\Listeners;


use App\Jobs\AddGoogleRating;
use App\Jobs\AddReviewBadgeCollectionJob;
use App\Jobs\AddReviewBadgeJob;
use App\Jobs\AddReviewBoxJob;
use App\Jobs\InjectAssetCoreJob;
use Illuminate\Support\Facades\Log;
use App\Services\ShopifyMetafieldService;
use App\Events\ChangedThemeSettingEvent;
use App\Events\ProductActionReview;
use App\Events\UserUpgrade;
use App\Events\UserDowngrade;
use App\Services\ReviewService;
use App\Events\ChangedShopifyThemeEvent;
use App\Services\ShopifyThemeService;
use App\Jobs\AddAssetCoreFileJob;
use App\Jobs\UpdateProductMetafieldJob;

class ReviewEventSubscriber
{





    /**
     * Create the event subscriber.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle change theme setting event
     *
     * @param  ChangedThemeSettingEvent  $event
     * @return void
     */
    public function onChangedThemeSetting($event)
    {
        dispatch(new AddAssetCoreFileJob($event->shopId, $event->shopDomain, $event->accessToken));
    }

    /**
     * Handle user changed plan
     *
     * @param  UserUpgrade|UserDowngrade $event
     * @return void
     */
    public function onChangedPlanSetting($event)
    {
        
    }

    public function onProductActionReview($event)
    {
        // unimplemented
    }

    /**
     * Handle change theme to update review box template
     *
     * @param  ChangedShopifyThemeEvent $event
     * @return void
     */
    public function onChangedReviewPublish($event)
    {
        dispatch(new InjectAssetCoreJob($event->shopId, $event->shopDomain, $event->accessToken));
        dispatch(new AddAssetCoreFileJob($event->shopId, $event->shopDomain, $event->accessToken));
        dispatch(new AddReviewBadgeJob($event->shopId, $event->shopDomain, $event->accessToken));
        dispatch(new AddReviewBoxJob($event->shopId, $event->shopDomain, $event->accessToken));
	    dispatch(new AddGoogleRating($event->shopId,$event->shopDomain,$event->accessToken));
    }

    /**
     * Handle change theme to update review badge template
     *
     * @param  ChangedShopifyThemeEvent $event
     * @return void
     */
    public function onChangedThemeUpdateReviewBadgeTemplate($event)
    {
        dispatch(new AddAssetCoreFileJob($event->shopId, $event->shopDomain, $event->accessToken));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ChangedThemeSettingEvent::class,
            'App\Listeners\ReviewEventSubscriber@onChangedThemeSetting'
        );
        $events->listen(
            ChangedShopifyThemeEvent::class,
            'App\Listeners\ReviewEventSubscriber@onChangedReviewPublish'
        );
    }
}
