<?php
/**
 * Created by PhpStorm.
 * User: fireapps
 * Date: 28/09/2018
 * Time: 17:12
 */

namespace App\Http\Middleware;

use App\Factory\RepositoryFactory;
use Closure;

class AllowUserUninstall
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

        try{
            $shopRepo = RepositoryFactory::shopsReposity();

            $shopInfo = $shopRepo->detail(['shop_id' => session('shopId')]);

            if ($shopInfo['status']) {

                $shopInfo    = $shopInfo['shopInfo'];

                $condition = $shopInfo->app_plan !== 'free' && empty($shopInfo->billing_id);

                if (!$condition) {
                    return redirect(route('apps.dashboard'));

                }

                return $next($request);

            }

        } catch (\Exception $exception)
        {
            $this->sentry->captureException($exception);

            return redirect(route('apps.dashboard'));
        }
    }
}
