<?php
/**
 * Created by PhpStorm.
 * User: fireapps
 * Date: 20/11/2018
 * Time: 11:17
 */

namespace App\Listeners;

use App\Jobs\ImportProductsFromApi;
use App\Jobs\WebHookApps;
class UpdateProductCS
{


    /**
     * UpdateProductCS constructor.
     */
    public function __construct()
    {

    }


    public function handle( $event)
    {
        $shopId         = $event->shopId;
        $accessToken    = $event->accessToken;
        $shopDomain     = $event->shopDomain;
        dispatch(new ImportProductsFromApi($shopId , $accessToken ,$shopDomain ));
        dispatch(new WebHookApps($accessToken, $shopDomain));
    }
}