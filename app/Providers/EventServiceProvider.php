<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ShopInstall' => [
            'App\Listeners\RegisterWebhook',
            'App\Listeners\UpdateDatabase',
            'App\Listeners\InitDatabase',
            'App\Listeners\ImportProduct',
            'App\Listeners\AddCodeAliReviewListener',
	        'App\Listeners\AddGoogleRatingAssetsListener',
        ],
	    'App\Events\LogRecordEvent' => [
	        'App\Listeners\LogRecordListener',
        ],
        'App\Events\UserDowngrade' => [
            'App\Listeners\RemoveReview'
        ],
        'App\Events\UserUpgrade' => [
            'App\Listeners\UpgradeReview'
        ],
        'App\Events\BeforeImportReviewEvent' => [
            'App\Listeners\BeforeImportReviewListener',
        ],
        'App\Events\CreatedReviewPageEvent' => [
            'App\Listeners\CreatedReviewPageListener',
        ],
        'App\Events\UpdateCache' => [
            'App\Listeners\UpdateCacheListener'
        ],
	    'App\Events\SaveIntercomEvent'       => [
		    'App\Listeners\SaveIntercomEventListeners'
        ],
        'App\Events\AddGoogleRatingEvent'       => [
//	        'App\Listeners\AddGoogleRatingAssetsListener',
	        'App\Listeners\AddGoogleRatingMetafieldListener',
        ],
        'App\Events\UpdateProductCS' => [
            'App\Listeners\UpdateProductCS'
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\ReviewEventSubscriber',
        'App\Listeners\AssetThemeSubscriber',
        'App\Listeners\WidgetReviewSubscriber',
        'App\Listeners\ResetSettingDefault',
        'App\Listeners\UpdateDatabaseSubscriber',
        'App\Listeners\UpdateSettingAppCS'
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
