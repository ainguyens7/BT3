<?php

namespace App\Listeners;

use App\Events\SavedSettingsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\LogRecordEvent;

class AddedKeyworkFilterListener
{
    /**
     * Handle the event.
     *
     * @param  SavedSettingsEvent  $event
     * @return void
     */
    public function handle(SavedSettingsEvent $event)
    {
        if (!empty($event->data['setting']['except_keyword']) && $event->status) {
            event(new LogRecordEvent($event->shopId, 'keyword_filter'));
        }            
    }
}
