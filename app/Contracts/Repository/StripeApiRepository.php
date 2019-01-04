<?php

namespace Contract\Repository;

/**
 * Interface StripeApiRepository
 * @package Contract\Repository
 */
interface StripeApiRepository
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createCustomer(array $data = []);

    /**
     * @return mixed
     */
    public function getPlan();

    /**
     * @return mixed
     */
    public function chargeMoneyInPlan();
}