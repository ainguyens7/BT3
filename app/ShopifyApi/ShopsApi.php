<?php

namespace App\ShopifyApi;


use App\Contracts\ShopifyAPI\ShopsApiInterface;
use App\Services\ShopifyServices;

class ShopsApi extends ShopifyServices implements ShopsApiInterface
{
    /**
     * @return array
     */
    public function get()
    {
        try{
            $shop = $this->_shopify->call([
                'URL' => 'shop.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'shop' => $shop->shop];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}