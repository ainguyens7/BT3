<?php

namespace App\Listeners;

use App\Events\UpdateApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\WorkerApi;
use App\Factory\RepositoryFactory;
use App\Services\UpdateProductMetafieldService;
use Exception;

class UpdateProductMetafield
{
    protected $sentry;

    protected $productRepo;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sentry = app('sentry');
        $this->productRepo = RepositoryFactory::productsRepository();
    }

    /**
     * Handle the event.
     *
     * @param  UpdateApp  $event
     * @return void
     */
    public function handle(UpdateApp $event)
    {
        // $shopId = $event->shopId;
        // $accessToken = $event->accessToken;
        // $shopDomain = $event->shopDomain;

        // $this->sentry->user_context([
        //     'shopDomain' => $shopDomain
        // ]);

        // $data = [
        //     'access_token' => $accessToken,
        //     'shopify_domain' => $shopDomain
        // ];

        // $findProduct = $this->productRepo->getAllProductSupportWorker($shopId);
        // if (!$findProduct['status']) {
        //     return;
        // }

        // $products = $findProduct['products']->all();
        // try {
        //     UpdateProductMetafieldService::update($products, $data);
        // } catch (Exception $ex) {
        //     $this->sentry->captureException($ex);
        // }
    }
}
