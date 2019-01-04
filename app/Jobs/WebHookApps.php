<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\ShopifyApi\WebHookApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class WebHookApps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 2;

    /**
     * @var WebHookApi
     */
    private $_webHookApi;

    /**
     * @var
     */
    private $_accessToken;

    /**
     * @var
     */
    private $_shopDomain;

    /**
     * WebHookApps constructor.
     * @param $accessToken
     * @param $shopDomain
     */
    public function __construct($accessToken, $shopDomain)
    {
        $this->_webHookApi = app(WebHookApi::class);
        $this->_accessToken = $accessToken;
        $this->_shopDomain = $shopDomain;
    }

    /**
     * @return array
     */
    private function listWebHook()
    {
        return [
            [
                'address' => route('webhook.uninstall_app'),
                'topic'   => 'app/uninstalled'
            ],
            [
                'address' => route('webhook.delete_product'),
                'topic'   => 'products/delete'
            ],
            [
                'address' => route('webhook.create_product'),
                'topic'   => 'products/create'
            ],
            [
                'address' => route('webhook.update_product'),
                'topic'   => 'products/update'
            ],
            [
                'address' => route('webhook.themes_update'),
                'topic'   => 'themes/update'
            ],
            [
                'address' => route('webhook.themes_publish'),
                'topic'   => 'themes/publish'
            ]
        ];
    }


    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->_webHookApi->getAccessToken($this->_accessToken, $this->_shopDomain);
        $sentry = app('sentry');
        $sentry->user_context([
            'shopDomain' => $this->_shopDomain
        ]);

        //Delete all webHook before add Web Hook
        $this->deleteAllWebHook();


        foreach ($this->listWebHook() as $k => $v)
        {
            $webHook = $this->_webHookApi->addWebHook($v['address'], $v['topic']);
            $sentry->captureMessage("Register webhook %s", [$this->_shopDomain],
                [
                    'extra' => [
                        'webhook' => $webHook
                    ],
                    'level' => 'info',
                    'culprit' => 'shopify.api.webhook.add.'.$this->_shopDomain.'.'.str_replace('/', '.', $v['topic'])
                ]
            );
        }
    }

    /**
     * @return bool
     */
    public function deleteAllWebHook()
    {
        $sentry = app('sentry');
        $webHook = $this->_webHookApi->allWebHook();
        if (!$webHook['status']) {
            $sentry->captureMessage('Get all webhook fail %s', [$this->_shopDomain], [
                'extra' => [
                    'webhook' => $webHook
                ],
                'culprit' => 'shopify.api.webhook.all.'.$this->_shopDomain,
                'level' => 'info'
            ]);
        } else {
            foreach ($webHook['webHook'] as $k => $v) {
                $isDelete = $this->_webHookApi->delete($v->id);
                $sentry->captureMessage("Delete webhook %s", [$this->_shopDomain, $v->topic], [
                    'extra' => [
                        'webhook' => $isDelete
                    ],
                    'level' => 'info',
                    'culprit' => 'shopify.api.webhook.delete.'.$this->_shopDomain.'.'.str_replace('/', '.', $v->topic)
                ]);
            }
        }
    }

}
