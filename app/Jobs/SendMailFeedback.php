<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Mail\Feedback;
use App\Mail\FrontendNewReviews;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $res;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * SendMailFeedback constructor.
     * @param $res
     */
    public function __construct($res)
    {
        $this->res = $res;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    Mail::to('support@fireapps.io')->send(new Feedback($this->res));
    }
}
