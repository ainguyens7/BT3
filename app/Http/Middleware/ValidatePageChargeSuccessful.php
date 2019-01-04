<?php

namespace App\Http\Middleware;

use Closure;

class ValidatePageChargeSuccessful
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
        $shopId = $request->session()->get('shopId', '');
        $accessToken = $request->session()->get('accessToken', '');
        if (empty($shopId) || empty($accessToken)) {
            return redirect(route('apps.installApp'));
        }
        return $next($request);
    }
}
