<?php

namespace App\Http\Middleware;

use App\Factory\RepositoryFactory;
use App\Http\Controllers\AppsController;
use App\Services\ShopifyServices;
use Closure;

class ShopifyCheck
{
    protected $sentry;
    public function __construct()
    {
        $this->sentry = app('sentry');
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $req = $request->all();
	    $shopifyService = new ShopifyServices();
        if(isset($req['shop']) and ($req['shop'] != session('shopDomain')))
        {
            //Re auth
            $shopifyService->setShopDomain($req['shop']);
            $installUrl = $shopifyService->installURL();
            return redirect($installUrl);
        }
        try{
	        $shopRepo = RepositoryFactory::shopsReposity();
	        $shopInfo = $shopRepo->detail(['shop_id' => session('shopId')]);
	        if($shopInfo['status']) {
		        $shopInfo    = $shopInfo['shopInfo'];
		        if ( $shopInfo->shop_status == config( 'common.status.unpublish' ) )
			        return redirect( route( 'apps.installApp' ) );

		        if(empty($shopInfo->app_plan)){
			        $currentRouteName = \Illuminate\Support\Facades\Request::route()->getName();
			        $array_route_not_in = [
				        'apps.planInfo','apps.addDiscountHandle','apps.addDiscount','apps.addCharged','apps.chargeHandle','apps.upgradeHandle','apps.checkDiscount'
			        ];
			        if(!in_array($currentRouteName,$array_route_not_in))
				        return redirect( route( 'apps.choicePlan' ) );
		        }
	        }

	        $shopifyService->getAccessToken(session('accessToken'), session('shopDomain'));
	        return $next($request);
        } catch (\Exception $exception)
        {
            $eventId = $this->sentry->captureException($exception);
            if(isset($req['shop']))
            {
                //Re auth
                $shopifyService->setShopDomain($req['shop']);
                $installUrl = $shopifyService->installURL();
                return redirect($installUrl);
            }
            return redirect(route('apps.installApp'))->with('error', $exception->getMessage());
        }
    }
}
