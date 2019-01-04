<?php

namespace App\Listeners;

use App\Events\BeforeImportReviewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\LogRecordEvent;

class BeforeImportReviewListener
{
    /**
     * Handle the event.
     *
     * @param  BeforeImportReviewEvent  $event
     * @return void
     */
    public function handle(BeforeImportReviewEvent $event)
    {
        event(new LogRecordEvent($event->shopId, 'import_review'));
    }
}
