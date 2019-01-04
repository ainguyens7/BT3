<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Mail\FrontendNewReviews;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailFrontendReviews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $res;
    private $shop_info;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * SendMailFrontendReviews constructor.
     * @param $shop_info
     * @param $res
     */
    public function __construct($shop_info, $res)
    {
        $this->res = $res;
        $this->shop_info = $shop_info;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shop_info = $this->shop_info;
//        Helpers::saveLog('info',['message' => 'Hello world, job']);

        if(isset($shop_info->shop_email) && $shop_info->shop_email != '') {
            Mail::to($shop_info->shop_email)->send(new FrontendNewReviews($this->res, $shop_info));
        }
    }
}
