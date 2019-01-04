<?php

namespace App\ShopifyApi;

use App\Services\ShopifyServices;
use Exception;

class ThemesApi extends ShopifyServices
{
    /**
     * @return array
     */
    public function getAllTheme()
    {
        try{
            $allTheme = $this->_shopify->call([
                'URL' => 'themes.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'allTheme' => $allTheme->themes];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}
