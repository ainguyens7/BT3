<?php

namespace App\Listeners;

use App\Facades\WorkerApi;
use Illuminate\Support\Facades\URL;
use App\Services\AssetThemeService;
use App\Jobs\InjectAssetCoreJob;
use App\Jobs\AddAssetCoreFileJob;;
use App\Jobs\WebHookApps;
use App\Jobs\ScriptTagApps;

class AssetThemeSubscriber
{
    protected $sentry;
    protected $assetService;

    public function __construct()
    {
        $this->sentry = app('sentry');
        $this->assetService = new AssetThemeService();

    }
    /**
     * Inject asset
     */
    public function injectAsset($event)
    {
        $shopId = $event->shopId;
        $shopDomain = $event->shopDomain;
        $accessToken = $event->accessToken;
        dispatch(new ScriptTagApps($accessToken, $shopDomain));
        dispatch(new AddAssetCoreFileJob($shopId, $shopDomain, $accessToken));
    }


    public function injectTemplateLoadAsset($event)
    {
        $shopId = $event->shopId;
        $shopDomain = $event->shopDomain;
        $accessToken = $event->accessToken;
        dispatch(new InjectAssetCoreJob($shopId, $shopDomain, $accessToken));
    }

    public function registerWebhook($event)
    {
        $shopId = $event->shopId;
        $shopDomain = $event->shopDomain;
        $accessToken = $event->accessToken;
        dispatch(new WebHookApps($accessToken, $shopDomain));
    }

    /**
     * Register multi listeners
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ShopInstall',
            'App\Listeners\AssetThemeSubscriber@injectTemplateLoadAsset'
        );
        $events->listen(
            'App\Events\ShopInstall',
            'App\Listeners\AssetThemeSubscriber@injectAsset'
        );

        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\AssetThemeSubscriber@injectTemplateLoadAsset'
        );

        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\AssetThemeSubscriber@injectAsset'
        );

        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\AssetThemeSubscriber@registerWebhook'
        );

        $events->listen(
            'App\Events\ShopReinstall',
            'App\Listeners\AssetThemeSubscriber@registerWebhook'
        );
    }
}