<?php

namespace App\Listeners;

use App\Events\UpdateApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use App\Facades\WorkerApi;


use Exception;

class AddScriptTag
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
     * @param  UpdateApp  $event
     * @return void
     */
    public function handle(UpdateApp $event)
    {
        $shopId = $event->shopId;
        $accessToken = $event->accessToken;
        $shopDomain = $event->shopDomain;

        $this->sentry->user_context([
            'shopId' => $shopId,
            'shopDomain' => $shopDomain
        ]);

        $data = [
            'shopify_domain' => $shopDomain,
            'access_token' => $accessToken,
            'script_tag_src' => $this->templateScript()
        ];
        try {
            // silence call
            WorkerApi::callApi('add_script_tag', $data);
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
        }
    }


    private function templateScript()
    {
        $src = URL::asset('js/improve/comment.js');
        return $src;
    }
}
