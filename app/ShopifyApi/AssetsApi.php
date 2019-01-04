<?php

namespace App\ShopifyApi;

use App\Services\ShopifyServices;
use Exception;

class AssetsApi extends ShopifyServices
{
    /**
     * @param $currentTheme
     * @param $fileAsset
     * @return array
     */
    public function getAssetValue($currentTheme, $fileAsset)
    {
        try {
            $assetValue = $this->_shopify->call([
                'URL' => '/admin/themes/'.$currentTheme.'/assets.json?asset[key]='.$fileAsset,
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'assetValue' => $assetValue->asset];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param $currentTheme
     * @param $fileAsset
     * @param $newAssetValue
     * @return array
     */
    public function updateAssetValue($currentTheme = '',$fileAsset = '', $newAssetValue = '')
    {
        try {
            $this->_shopify->call([
                'URL' => 'themes/'.$currentTheme.'/assets.json',
                'METHOD' => 'PUT',
                'DATA' => [
                    'asset' => [
                        'key' => $fileAsset,
                        'value' => $newAssetValue
                    ]
                ]
            ]);
            return ['status' => true];

        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}