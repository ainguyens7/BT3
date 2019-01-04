<?php
/**
 * Created by PhpStorm.
 * User: buicongdang
 * Date: 8/30/17
 * Time: 1:28 PM
 */

namespace App\ShopifyApi;


use App\Services\ShopifyServices;

class CustomeCollectionsApi extends ShopifyServices
{
    public function all($limit = 250)
    {
        try{
            $customCollections = $this->_shopify->call([
                'URL' => 'custom_collections.json',
                'METHOD' => 'GET',
                'DATA' => [
                    'limit' => $limit
                ]
            ]);
            return ['status' => true, 'customCollections' => $customCollections->custom_collections];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }

    }
}