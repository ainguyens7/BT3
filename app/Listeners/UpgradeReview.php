<?php

namespace App\Listeners;

use App\Events\UserUpgrade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Factory\RepositoryFactory;

class UpgradeReview
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
     * @param  UserUpgrade  $event
     * @return void
     */
    public function handle(UserUpgrade $event)
    {
        $shopId = $event->shopId;
        $currentPlan = $event->currentPlan;
        $upgradeTo = $event->upgradeTo;

        if (($currentPlan === 'premium' || $currentPlan === 'free') && $upgradeTo === 'unlimited') {
            // enable all source
            return $this->handleUpgradeToUnlimited($shopId);
        }

        if ($currentPlan === 'free' && $upgradeTo === 'premium') {
            return $this->handleUpgradeToPremium($shopId);
        }
    }

    private function handleUpgradeToUnlimited($shopId)
    {
        return $this->updateReviewObject($shopId, ['aliexpress', 'oberlo', 'default','aliorder'], ['status' =>  1]);
    }

    private function handleUpgradeToPremium($shopId)
    {
        return $this->updateReviewObject($shopId, ['aliexpress', 'default', 'aliorder' ], ['status' =>  1]);
    }

    private function updateReviewObject($shopId = '', $source = 'default', $data = ['status' => 1])
    {
        return $this->commentBackendRepo->updateAllCommentBySource($shopId, $source, $data);        
    }
}
