<?php

namespace App\Http\Middleware;

use Closure;

class IsAjaxRequest
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
        $result = [];
        if (!$request->ajax()) {
            $result['status'] = trans('api.status', ['status' => 'error']);
            $result['message'] = trans('api.method_not_allowed');
            return response()->json($result, 400);
        }
        return $next($request);
    }
}
