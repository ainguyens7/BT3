<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Repository\ShopMetaRepository;
use App\ShopifyApi\MetaFieldApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MetaFieldApps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_accessToken;
    private $_shopDomain;
    private $_shopId;
    private $_metaFieldApi;
    private $_shopMetaRepo;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 3;

    /**
     * MetaFieldApps constructor.
     * @param $accessToken
     * @param $shopDomain
     */
    public function __construct($accessToken, $shopDomain, $shopId)
    {
        $this->_accessToken = $accessToken;
        $this->_shopDomain = $shopDomain;
        $this->_shopId = $shopId;
        /**
         * @var MetaFieldApi
         */
        $this->_metaFieldApi = app(MetaFieldApi::class);
        /**
         * @var ShopMetaRepository
         */
        $this->_shopMetaRepo = app(ShopMetaRepository::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $strMetaField = '<div class="shop_info" shop-id="'.$this->_shopId.'" shop-name="'.$this->_shopDomain.'" style="display: none;">
                            <div class="reviews"></div>
                        </div>';
        $this->_metaFieldApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $metaField = $this->_metaFieldApi->addMetaField('review_collector', 'review_code', $strMetaField);

        if( ! $metaField['status'])
        {
            Helpers::saveLog('error', ['message' => $metaField['message'], 'file' => __FILE__, 'line' => __LINE__, 'function' => __FUNCTION__, 'domain' => $this->_shopDomain]);
        }
    }
}
