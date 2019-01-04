<?php
namespace App\ShopifyApi;


use App\Services\ShopifyServices;
use Exception;

class WebHookApi extends ShopifyServices
{
    /**
     * @param string $address
     * @param string $topic
     * @return array
     */
    public function addWebHook(string $address, string $topic) : array
    {
        try {
            $webHook = $this->_shopify->call([
                'URL' => 'webhooks.json',
                'METHOD' => 'POST',
                'DATA' => [
                    'webhook' => [
                        'address'  => $address,
                        'topic'    => $topic,
                        'format'   => 'json'
                    ]
                ]
            ]);
            return ['status' => true, 'webHook' => $webHook->webhook];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }

    }

    /**
     * @return array
     */
    public function allWebHook() : array
    {
        try{
            $webHook = $this->_shopify->call([
                'URL' => 'webhooks.json',
                'METHOD' => 'GET',
            ]);
            return ['status' => true, 'webHook' => $webHook->webhooks];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param $webHookId
     * @return array
     */
    public function detail($webHookId) : array
    {
        try{
            $webHook = $this->_shopify->call([
               'URL' => 'webhooks/'.$webHookId.'.json',
               'METHOD' => 'GET'
            ]);
            return ['status' => true, 'webHook' => $webHook->webhook];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param $webHookId
     * @return array
     */
    public function delete($webHookId) : array
    {
        try {
            $this->_shopify->call([
                'URL' => 'webhooks/'.$webHookId.'.json',
                'METHOD' => 'DELETE'
            ]);
            return ['status' => true];
        } catch (Exception $exception)
        {
            $this->sentry->captureException($exception);
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

}