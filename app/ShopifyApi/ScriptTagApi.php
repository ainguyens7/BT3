<?php

namespace App\ShopifyApi;


use App\Services\ShopifyServices;

class ScriptTagApi extends ShopifyServices
{
    /**
     * @param $scriptUrl
     * @return array
     */
    public function addScriptTag($scriptUrl)
    {
        try{
            $scriptTag = $this->_shopify->call([
                'URL' => 'script_tags.json',
                'METHOD' => 'POST',
                'DATA' => [
                    'script_tag' => [
                        'event' => 'onload',
                        'src' => $scriptUrl
                    ]
                ]
            ]);
            return ['status' => true, 'scriptTag' => $scriptTag->script_tag];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @return array
     */
    public function allScriptTag()
    {
        try{
            $scriptTag = $this->_shopify->call([
                'URL' => 'script_tags.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'scriptTag' => $scriptTag->script_tags];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param $scriptTagId
     * @return array
     */
    public function deleteScriptTag($scriptTagId)
    {
        try{
            $this->_shopify->call([
                'URL' => 'script_tags/'.$scriptTagId.'.json',
                'METHOD' => 'DELETE'
            ]);
            return ['status' => true];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

}