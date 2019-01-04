<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Models\CommentsModel;
use App\Models\ShopMetaModel;
use App\Repository\CommentBackEndRepository;
use App\Repository\ShopMetaRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InitDatabaseApps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_shopMetaRepo;
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_commentBackendRepo;
    /**
     * @var
     */
    private $_shopId;

    private $_shopMetaModel;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
    public $tries = 1;
    
    private $sentry;

    /**
     * InitDatabaseApps constructor.
     * @param $shopId
     */
    public function __construct($shopId)
    {
        $this->_shopId = $shopId;
        $this->sentry = app('sentry');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $_shopMetaRepo =  app(ShopMetaRepository::class);
            $_commentBackendRepo = app(CommentBackEndRepository::class);
            $settingDefault = config('settings');
            $settingDefault['shop_id'] = $this->_shopId;
            //Create Shop Meta
            $_shopMetaRepo->create($settingDefault);
    
            //Create table comment_shop_id
            $_commentBackendRepo->createTable($this->_shopId);
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
        }
        
    }
}
