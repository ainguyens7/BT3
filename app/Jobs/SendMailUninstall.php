<?php

namespace App\Jobs;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Mail\Feedback;
use App\Mail\FrontendNewReviews;
use App\Mail\Uninstall;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailUninstall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopId;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
	public $tries = 3;

	/**
     * SendMailFeedback constructor.
     * @param $shopId
     */
    public function __construct($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $shopRepo = RepositoryFactory::shopsReposity();
	    $shopInfo = $shopRepo->detail(['shop_id' => $this->shopId]);
	    if(!empty($shopInfo['status'])){
		    $shopInfo = $shopInfo['shopInfo'];
		    Mail::to($shopInfo->shop_email)->send(new Uninstall($shopInfo));
	    }
    }
}
