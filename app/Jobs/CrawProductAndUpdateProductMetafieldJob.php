<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Factory\RepositoryFactory;
use App\Jobs\UpdateProductMetafieldJob;

class CrawProductAndUpdateProductMetafieldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shopId;
    public $accessToken;
    public $shopDomain;
    public $productRepo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId, $shopDomain, $accessToken)
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->accessToken = $accessToken;
        $this->productRepo = RepositoryFactory::productsRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $findProduct = $this->productRepo->getAll($this->shopId);
        if (!$findProduct['status']) {
            return;
        }

        $result = $findProduct['products'];
        $nextPage = $result->currentPage() + 1;
        $lastPage = $result->lastPage();
        // update current page
        $items = $result->items();

        foreach ($items as $item) {
           dispatch(new UpdateProductMetafieldJob($this->shopId, $this->shopDomain, $this->accessToken, $item->id));
           sleep(3);
        }

        for ($i = $nextPage; $i <= $lastPage; $i++) {
            $findProduct = $this->productRepo->getAll($this->shopId, ['currentPage' => $i]);
            if ($findProduct['status']) {
                $items = $findProduct['products']->items();
                foreach($items as $item) {
                    dispatch(new UpdateProductMetafieldJob($this->shopId, $this->shopDomain, $this->accessToken, $item->id));
                    sleep(3);
                }
            }
            
        }
    }
}
