<?php

namespace App\Services;

use App\Events\ChangedThemeSettingEvent;
use App\Factory\RepositoryFactory;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Events\ChangedShopifyThemeEvent;

class ShopifyWebHookService
{
    /**
     * Duplicated with @verifyWebhook
     * At App\Http\Controllers\WebHookController:204
     */

    /**
     * Verify shopify request from webhook
     * 
     * @param Request $request
     * @return boolean
     */
    public static function verifyRequest($hmacHeader) {
        if ($hmacHeader) {
            $calculatedHmac = base64_encode(hash_hmac(
                'sha256',
                file_get_contents('php://input'),
                env('API_SECRET'),
                true
            ));
            return ($hmacHeader == $calculatedHmac);
        }
        return false;
    }

    /**
     * Handle theme update webhook
     *
     * @param String shopName
     * @param String hmacHeader
     * @return void
     */
    public static function handleThemeUpdate($shopName, $hmacHeader) {
        if (self::verifyRequest($hmacHeader)) {
            Log::info('Theme update verified');
            // Handle event here
            return;
        }
        Log::error('Webhook theme update not verify');
    }

    /**
     * Handle theme publish webhook
     *
     * @param String shopName
     * @param String hmacHeader
     * @return void
     */
    public static function handleThemePublish($shopName, $hmacHeader) {
        if (self::verifyRequest($hmacHeader)) {
            Log::info('Theme publish verified');
            $shopRepo = RepositoryFactory::shopsReposity();
            $objectShop = $shopRepo->detail(array('shop_name' => $shopName));
            if ($objectShop['status']) {
                $shopInfo = $objectShop['shopInfo'];
                event(new ChangedShopifyThemeEvent($shopInfo->shop_id,$shopInfo->shop_name,$shopInfo->access_token));
            }
            return;
        }
        Log::error('Webhook theme publish not verify');
    }
    /**
     * Handle customers redact webhook
     *
     * @param String hmacHeader
     * @return void
     */
    public static function handleCustomersRedact($hmacHeader) {
        $sentry = app('sentry');
        try {
            if (self::verifyRequest($hmacHeader)) {
                Log::info('Webhook customers redact verified');
                // Handle event here
                return true;
            }
            Log::error('Webhook customers redact not verify');
            return false;
        }
        catch (Exception $ex) {
            $sentry->captureException($ex);
            return false;
        }
    }

    /**
     * Handle shop redact webhook
     *
     * @param string shopId
     * @param string hmacHeader
     * @return void
     */
    public static function handleShopRedact($shopId, $hmacHeader) {
        $sentry = app('sentry');
        $sentry->user_context([
            'shopId' => $shopId
        ]);
        try {
            if (self::verifyRequest($hmacHeader)) {
                Log::info('Webhook shop redact verified');
                // Handle event here
                $shopRepo = RepositoryFactory::shopsReposity();
                return $shopRepo->delete($shopId);
            }
            Log::error('Webhook shop redact not verify');
            return false;
        } catch (Exception $ex) {
            $sentry->captureException($ex);
            return false;
        }
    }
}
