<?php

namespace App\Services;

use App\Helpers\Helpers;
use App\Factory\RepositoryFactory;
use App\Facades\WorkerApi;
use Illuminate\Support\Facades\URL;
use App\Services\ReviewService;

class UpdateAppService
{

    private $_productRepo;

    protected $sentry;

    public function __construct()
    {
        $this->_productRepo = RepositoryFactory::productsRepository();
        $this->sentry = app('sentry');
    }

    /**
     * @return void
     */
    public function runInternalUpdate() 
    {
        $shopId = session('shopId', '');
        $shopifyDomain = session('shopDomain', '');
        $token = session('accessToken', '');

        $this->sentry->user_context([
            'shopId' => $shopId,
            'shopify_domain' => $shopifyDomain
        ]);

        $prepareData = [
            'shop_id' => $shopId,
            'shopify_domain' => $shopifyDomain,
            'access_token' => $token
        ];

        // InjectAssetScript
        $src = URL::asset('js/frontend/comment.js');
        $scriptTag = "<script src=\"{$src}\"></script>";
        $result = WorkerApi::callApi('inject_script_tag', array_merge($prepareData, ['script_tag' => $scriptTag]));

    }
}
