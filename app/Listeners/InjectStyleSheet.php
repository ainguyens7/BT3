<?php

namespace App\Listeners;

use App\Events\UpdateApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use App\Facades\WorkerApi;

class InjectStyleSheet
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
            'value' => $this->styleSheets()
        ];
        try {
            // silence call
            WorkerApi::callApi('inject_asset', $data);
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
        }
    }


    private function styleSheets()
    {
        $lightbox = URL::asset('css/frontend/libs/light-box/lightbox.css');
        $rating = URL::asset('css/frontend/libs/star-rating/rating.css');
        $linkLightBox = "<link rel='stylesheet' href='{$lightbox}' />";
        $linkRating = "<link rel='stylesheet' href='{$rating}' />";
        $scriptTag = $this->_scriptTags();
        return "{$linkFont} \n {$linkLightBox} \n {$linkRating} \n {$scriptTag} \n";
    }
    private function _scriptTags()
    {
        $comment = URL::asset('js/improve/comment.js');
        // $frontend = URL::asset('js/frontend/frontend.js');
        // $rating = URL::asset('js/frontend/rating-3.js');
        $commentTag = config('assets.jquery');
        $commentTag .= "<script src='{$comment}'></script>";
        // $frontendTag = "<script async src='{$frontend}'></script>";
        // $ratingTag = "<script async src='{$rating}'></script>";
        return "{$commentTag}";
    }
}
