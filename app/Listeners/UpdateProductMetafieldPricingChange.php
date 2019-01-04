<?php

namespace App\Listeners;

use App\Events\PricingChange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\UpdateProductMetafieldService;
use App\Factory\RepositoryFactory;
use App\Jobs\UpdateProductMetafieldJob;
use Exception;

class UpdateProductMetafieldPricingChange
{
    /**
     * Handle the event.
     *
     * @param  PricingChange  $event
     * @return void
     */
    public function handle(PricingChange $event)
    {
        // $shopId = $event->shopId;
        // $shopDomain = $event->shopDomain;
        // $accessToken = $event->accessToken;

        // $productRepo = RepositoryFactory::productsRepository();

        // $findProduct = $productRepo->getAll($shopId);
        // if (!$findProduct['status']) {
        //     return;
        // }

        // $result = $findProduct['products'];
        // $nextPage = $result->currentPage() + 1;
        // $lastPage = $result->lastPage();
        // // update current page
        // $items = $result->items();

        // foreach ($items as $item) {
        //    dispatch(new UpdateProductMetafieldJob($shopId, $shopDomain, $accessToken, $item->id));
        //    sleep(2);
        // }

        // for ($i = $nextPage; $i <= $lastPage; $i++) {
        //     $findProduct = $productRepo->getAll($shopId, ['currentPage' => $i]);
        //     if ($findProduct['status']) {
        //         $items = $findProduct['products']->items();
        //         foreach($items as $item) {
        //             dispatch(new UpdateProductMetafieldJob($shopId, $shopDomain, $accessToken, $item->id));
        //             sleep(2);
        //         }
        //     }
        // }
    }
}
