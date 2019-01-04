<?php
/**
 * Created by PhpStorm.
 * User: dev-08
 * Date: 9/5/2018
 * Time: 3:37 PM
 */

namespace App\Http\Middleware;
use App\Repository\ShopsRepository;
use Closure;


class ShopInstall
{
    protected $_shopRepo;
    private $sentry;
    public function __construct(ShopsRepository $shopRepository)
    {
        $this->_shopRepo  = $shopRepository ;

        $this->sentry = app('sentry');
    }
    public function handle($request, Closure $next)
    {
        try{
            $shopName      = $request->shopName;
            $shopId        = $request->shopId;
            $shopNameJson  = $request->shop;
            if(empty($shopName) && empty($shopNameJson) && empty($shopId) ){
                return response()->json(['status'=>'error' , 'message'=>__('api.error')]) ;
            }
            if(!empty($shopName)){
                $shop      = $this->_shopRepo->detail(['shop_name' => $shopName]);
            }
            if(!empty($shopId)){
                $shop      = $this->_shopRepo->detail(['shop_id' => $shopId]);
            }
            if(!empty($shopNameJson)) {
                $shop      = $this->_shopRepo->detail(['shop_name' => $shopNameJson]);
            }
            if ($shop['status']) {
                $shopInstall  = ($shop['shopInfo']['shop_status']); // && !is_null($shop['shopInfo']['billing_id']) &&  !is_null($shop['shopInfo']['billing_on'])
                if ($shopInstall) {
                    return $next($request);
                } else {
                    return response()->json(['status'=>'error', 'message'=>__('api.message_error_shop_install'), 'link' => 'https://apps.shopify.com/ali-reviews']) ;
                }
            } else {
                return response()->json(['status'=>'error', 'message'=>$shop['message']]) ;
            }
        } catch (\Exception $exception){

            $this->sentry->captureException($exception);
            return response()->json(['status'=>'error' , 'message'=>__('api.message_internal')]) ;
        }

    }
}