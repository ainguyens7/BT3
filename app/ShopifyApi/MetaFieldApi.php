<?php
namespace App\ShopifyApi;

use App\Services\ShopifyServices;
use Exception;

class MetaFieldApi extends ShopifyServices
{
    /**
     * @param $nameSpace
     * @param $key
     * @param $valueMetaField
     * @return array
     */
    public function addMetaField($nameSpace, $key, $valueMetaField)
    {
        try {
            $metaField = $this->_shopify->call([
                'URL' => 'metafields.json',
                'METHOD' => 'POST',
                'DATA' => [
                    'metafield' => [
                        'namespace'  => $nameSpace,
                        'key'        => $key,
                        'value'      => $valueMetaField,
                        'value_type' => 'string'
                    ]
                ]
            ]);
            return ['status' => true, 'metaField' => $metaField->metafield];
        }catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    public function addProductMetafield($productId, $nameSpace, $key, $valueMetaField)
    {
        try {
            $metaField = $this->_shopify->call([
                'URL' => "products/{$productId}/metafields.json",
                'METHOD' => 'POST',
                'DATA' => [
                    'metafield' => [
                        'namespace'  => $nameSpace,
                        'key'        => $key,
                        'value'      => $valueMetaField,
                        'value_type' => 'string'
                    ]
                ]
            ]);
            return ['status' => true, 'metaField' => $metaField->metafield];
        }catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @return array
     */
    public function allMetaField()
    {
        try{
            $metaField = $this->_shopify->call([
                'URL' => 'metafields.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'metaField' => $metaField->metafields];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}