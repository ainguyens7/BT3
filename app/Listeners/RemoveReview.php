<?php

namespace App\Listeners;

use App\Events\UserDowngrade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Factory\RepositoryFactory;

class RemoveReview
{

    private $commentBackendRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RepositoryFactory $factory)
    {
        $this->commentBackendRepo = $factory::commentBackEndRepository();
    }

    /**
     * Handle the event.
     *
     * @param  UserDowngrade  $event
     * @return void
     */
    public function handle(UserDowngrade $event)
    {
        $shopId = $event->shopId;
        $currentPlan = $event->currentPlan;
        $downgradeTo = $event->downgradeTo;

        if ($currentPlan === 'unlimited' && $downgradeTo === 'premium') {
            return $this->handleDowngradeUnlimitedToPremium($shopId);
        }

        if (($currentPlan === 'unlimited' || $currentPlan === 'premium') && $downgradeTo === 'free') {
            return $this->handleDowngradeToFree($shopId);
        }
    }

    private function handleDowngradeToFree($shopId)
    {
        // hide all except web
        $this->updateReviewObject($shopId, ['aliexpress', 'oberlo','aliorder']);

        $listProduct = $this->commentBackendRepo->getListProductReviewsOfPlanFree($shopId);

        if ($listProduct['status']){

            $listProduct  = $listProduct['result'];

            array_map(function ($item) use ($shopId){

                $this->commentBackendRepo->updateLimitAmountReviewDownPlan($shopId, $item);

            },$listProduct);
        }
    }

    private function handleDowngradeUnlimitedToPremium($shopId = '')
    {
        $this->hideReviewSourceOberlo($shopId);
    }

    private function updateReviewObject($shopId = '', $source = 'default', $data = ['status' => 0])
    {
        $this->commentBackendRepo->updateAllCommentBySource($shopId, $source, $data);        
    }
    
    private function hideReviewSourceOberlo($shopId = '')
    {
        $this->updateReviewObject($shopId, 'oberlo');
    }
}
