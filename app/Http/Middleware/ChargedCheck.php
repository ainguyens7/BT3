<?php

namespace App\Http\Middleware;

use App\Repository\ShopsRepository;
use App\ShopifyApi\ChargedApi;
use Closure;
use Exception;

class ChargedCheck
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
        $this->sentry->user_context([
            'request' => $request->all()
        ]);
        $chargedApi = new ChargedApi();
        /**
         * @var ShopsRepository
         */
        $shopRepo = app(ShopsRepository::class);
	    //Get shop info from database
	    $shopInfo = $shopRepo->detail(['shop_id' => session('shopId')]);

	    try{
            if($shopInfo['status'])
            {
	            if(!empty($shopInfo['shopInfo']->app_plan) && $shopInfo['shopInfo']->app_plan == 'free')
		            return $next($request);

                //Get charged info from api and check charge status "active"
                $chargedApi->getAccessToken(session('accessToken'), session('shopDomain'));
                $chargedInfo = $chargedApi->detailCharge($shopInfo['shopInfo']->billing_id);
            
                if($chargedInfo['status'])
                {
                    if($chargedInfo['detailCharge']->status == 'active')
                        return $next($request);
                }

                return redirect(route('apps.addCharged',['app_plan' => $shopInfo['shopInfo']->app_plan]));
            }

            return redirect(route('apps.errors404'));
        } catch (Exception $exception)
        {
            throw new Exception($exception);
        }
    }
}
