<?php

namespace App\Listeners;

use App\Services\UpdateProductMetafieldService;
use App\Factory\RepositoryFactory;
use App\Jobs\UpdateProductMetafieldJob;
use Exception;

class ProductMetaFieldSubscriber
{
    protected $productRepo;


    public function __construct()
    {
        $this->productRepo = RepositoryFactory::productsRepository();
    }

    public function updateProductMetafield($event)
    {
        // $shopId = $event->shopId;
        // $accessToken = $event->accessToken;
        // $shopDomain = $event->shopDomain;
        // $productIdList = $event->productIdList;
        // foreach ($productIdList as $productId) {
        //     dispatch(new UpdateProductMetafieldJob($shopId, $shopDomain, $accessToken, $productId));
        //     sleep(2);
        // }
    }

    public function processingAddProductMetafieldWhenUpdateApp($event)
    {
        // $shopId = $event->shopId;
        // $accessToken = $event->accessToken;
        // $shopDomain = $event->shopDomain;

        // $findProduct = $this->productRepo->getAll($shopId);
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
        //     $findProduct = $this->productRepo->getAll($shopId, ['currentPage' => $i]);
        //     if ($findProduct['status']) {
        //         $items = $findProduct['products']->items();
        //         foreach($items as $item) {
        //             dispatch(new UpdateProductMetafieldJob($shopId, $shopDomain, $accessToken, $item->id));
        //             sleep(2);
        //         }
        //     }
        // }
    }

    /**
     * Register multi listeners
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ImportedProductEvent',
            'App\Listeners\ProductMetaFieldSubscriber@updateProductMetafield'
        );

        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\ProductMetaFieldSubscriber@processingAddProductMetafieldWhenUpdateApp'
        );
      
        $events->listen(
            'App\Events\ChangeSetting',
            'App\Listeners\ProductMetaFieldSubscriber@processingAddProductMetafieldWhenUpdateApp'
        );
        
        // $events->listen(
        //     'App\Events\ChangedThemeSettingEvent',
        //     'App\Listeners\ProductMetaFieldSubscriber@processingAddProductMetafieldWhenUpdateApp'
        // );

        $events->listen(
            'App\Events\SaveLocalTranslate',
            'App\Listeners\ProductMetaFieldSubscriber@processingAddProductMetafieldWhenUpdateApp'
        );

    }
}