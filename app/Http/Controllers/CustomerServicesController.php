<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\WorkerApi;

class CustomerServicesController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import(Request $request)
    {
        $result = null;
        if ($request->has('result')) {
            $result = $request->input('result') === 'success';
        }
        return view('customerServices.importProduct', compact('result'));
    }

    /**
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function importHandle()
    {
        $result = WorkerApi::callApi('import_product', [
            'shop_id' => session('shopId')
        ]);
        $arg = !isset($result['error']) ? '?result=success' : '?result=error';
        return redirect(route('cs.importProduct') . $arg);
    }
}
