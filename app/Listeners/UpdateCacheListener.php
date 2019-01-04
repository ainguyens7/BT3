<?php

namespace App\Listeners;

use App\Events\UpdateCache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\Cache;

class UpdateCacheListener
{
    private $cacheFrontend;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cacheFrontend = app('CacheFrontend');
    }

    /**
     * Handle the event.
     *
     * @param  UpdateCache  $event
     * @return void
     */
    public function handle(UpdateCache $event)
    {
        $shopId = $event->shopId;
        $productId = $event->productId;

        if (!empty($productId)) {
            $keyHash = Helpers::generate_key_cache_hash($shopId, $productId, config('cache_frontend.key_hash_cache_frontend'));
            $this->cacheFrontend->del($keyHash);
            $keyHashReviewPage = Helpers::generate_key_cache_hash($shopId, 0, config('cache_frontend.key_hash_cache_review_page_frontend'));
            $this->cacheFrontend->del($keyHashReviewPage);
        } else if (empty($productId)) {
            $keyHashReviewPage = Helpers::generate_key_cache_hash($shopId, 0, config('cache_frontend.key_hash_cache_review_page_frontend'));
            $keys = $this->cacheFrontend->findKey($keyHashReviewPage);
            array_map(function($key) {
                return $this->cacheFrontend->del($key);
            }, $keys);

            $keyHash = Helpers::generate_key_cache_hash($shopId, '*', config('cache_frontend.key_hash_cache_frontend'));
            $keys = $this->cacheFrontend->findKey($keyHash);
            $mapDelete = array_map(function($key) {
                return $this->cacheFrontend->del($key);
            }, $keys);
        }

        $sources = ['all','web','import'];
        foreach ($sources as $key => $source) {
            if (Cache::has('countReviewProduct_'.$shopId .$source)) {
                Cache::forget('countReviewProduct_'.$shopId .$source);
            }
        }
	   
    }
}
