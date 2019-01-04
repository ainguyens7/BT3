<?php

namespace App\Listeners;

use App\Events\CreatedReviewPageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\LogRecordEvent;

class CreatedReviewPageListener
{
    /**
     * Handle the event.
     *
     * @param  CreatedReviewPageEvent  $event
     * @return void
     */
    public function handle(CreatedReviewPageEvent $event)
    {
        if ($event->status) {
            event(new LogRecordEvent($event->shopId, 'review_page'));
        } 
    }
}
