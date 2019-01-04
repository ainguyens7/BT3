<?php
/**
 * Created by PhpStorm.
 * User: fireapps
 * Date: 04/10/2018
 * Time: 13:08
 */

namespace App\Http\Middleware;

use App\Repository\ShopsRepository;
use Closure;
use Illuminate\Support\Facades\Route;

class AllowReviewFrontend
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
            $shopID             = ( isset($request->shop_id) && !empty($request->shop_id) ) ;
            $aliReviewShopId    = ( isset($request->alireview_shop_id) && !empty($request->alireview_shop_id) ) ;
            $shopDomain         = ( isset($request->shop_domain) && !empty($request->shop_domain) ) ;
            if ($shopID  || $aliReviewShopId || $shopDomain) {
                $shopId     = !empty($request->shop_id)  ? $request->shop_id :  $request->alireview_shop_id ;
                $shop       = !empty($shopId) ? $this->_shopRepo->detail(['shop_id' => $shopId]) :  $this->_shopRepo->detail(['shop_name' => $request->shop_domain]) ;
                if ($shop['status']) {

                    $shopInfo   = $shop['shopInfo'] ;

                    if ($shopInfo->shop_status === '0') return response()->json(['status' => false, 'message' => 'Cannot find review']);

                    if ($shopInfo->app_plan !== 'free' && empty($shopInfo->billing_id)) {
                        return response()->json(['status' => false, 'message' => 'Cannot find review']);
                    }
                    return $next($request);
                }
            }
            return response()->json(['status' => false, 'message' => 'Cannot find review']);
        } catch (\Exception $exception) {

            $this->sentry->captureException($exception);

            return response()->json(['status' => false, 'message' => 'Cannot find review']);
        }
    }
}