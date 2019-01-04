<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\ShopifyApi\ScriptTagApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\URL;

class ScriptTagApps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $_accessToken;
    private $_shopDomain;
    private $_scriptTagApi;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 3;

    /**
     * ScriptTagApps constructor.
     * @param $accessToken
     * @param $shopDomain
     */
    public function __construct($accessToken, $shopDomain)
    {
        $this->_accessToken = $accessToken;
        $this->_shopDomain = $shopDomain;
        $this->_scriptTagApi = app(ScriptTagApi::class);
    }

    /**
     * @return array
     */
    public function listScriptTag()
    {
        return [
            URL::asset('js/frontend/comment.js?v='.config('common')['app_version'])
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->_scriptTagApi->getAccessToken($this->_accessToken, $this->_shopDomain);

        //Delete all script tag
        $this->deleteAllScriptTag();
    }


    public function deleteAllScriptTag()
    {
        $scriptTag = $this->_scriptTagApi->allScriptTag();

        if($scriptTag['status'])
        {
            foreach($scriptTag['scriptTag'] as $k=>$v)
            {
                $isDeleteScriptTag = $this->_scriptTagApi->deleteScriptTag($v->id);
            }
        }
    }
}
