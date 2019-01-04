<?php

namespace App\Listeners;

use App\Events\UpdatedReviewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\ReviewService;
use Exception;

class UpdateReviewBadgeListener
{
    protected $sentry;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sentry = app('sentry');
    }

    /**
     * Handle the event.
     *
     * @param  UpdatedReviewEvent  $event
     * @return void
     */
    public function handle(UpdatedReviewEvent $event)
    {
        // try
        // {
        //     ReviewService::updatedReviewBadgeHandle(
        //         $event->shopId,
        //         $event->productId,
        //         $event->commentData,
        //         $event->shopifyDomain,
        //         $event->accessToken
        //     );
        // } catch(Exception $ex) {
        //     $this->sentry->captureException($ex);
        // }
    }
}
