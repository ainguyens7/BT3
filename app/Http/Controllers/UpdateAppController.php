<?php

namespace App\Http\Controllers;

use App\Events\UpdateApp;

class UpdateAppController extends Controller
{
    function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() 
    {
        return view('update.update');
    }

    /**
     * Process internal update
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexHandle() 
    {
        $shopId = session('shopId', '');
        $shopifyDomain = session('shopDomain', '');
        $accessToken = session('accessToken', '');

        event(new UpdateApp($shopId, $shopifyDomain, $accessToken));
        
        $result = array(
            'status'  => 'success',
            'message' => 'Update app successful.',
            'url'     => route('apps.getStart'),
        );
        return response()->json($result, 200);
    }
}
