<?php

namespace App\Contract\ShopifyAPI;


interface PriceRuleApiInterface
{
    /**
     * @param $discountCode
     * @return array
     */
    public function createPriceRule($discountCode) :array ;

//    public function createDiscountCodes($idPriceRule, $discountCode) ;

    /**
     * @return mixed
     */
    public function all();
}