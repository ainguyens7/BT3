<?php

namespace App\Jobs;

use App\Repository\ShopsRepository;
use App\Helpers\Helpers;
use App\Mail\UnuseReviewPage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailUnuseReviewPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopId;
    private $appURL;

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
        $this->appURL = env('APP_URL');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shopRepo = new ShopsRepository();
        $shopInfo = $shopRepo->detail(['shop_id' => $this->shopId]);
        if(!empty($shopInfo['status'])){
            $shopInfo = $shopInfo['shopInfo'];
            Mail::to($shopInfo->shop_email)->send(new UnuseReviewPage($shopInfo, $this->appURL));
        }
    }
}
