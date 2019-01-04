<?php

namespace App\Http\Middleware;

use App\Factory\RepositoryFactory;
use App\Jobs\ImportProductsFromApi;
use App\Jobs\ScriptTagApps;
use App\Jobs\UpdateDatabase;
use App\Jobs\WebHookApps;
use Closure;
use Illuminate\Http\Response;

class CheckVersionApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$shopRepo = RepositoryFactory::shopsReposity();
        $shop = $shopRepo->detail(['shop_name' => session('shopDomain')]);

	    if($shop['status']){
	        $shop_info = $shop['shopInfo'];

	        if(isset($shop_info->app_version) && $shop_info->app_version != config('common.app_version')){
		        return redirect(route('apps.updateApp'));
	        }

	        return $next($request);
        }

	    return redirect(route('apps.installApp'));
    }
}
