<?php

namespace App\Mail;

use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FrontendNewReviews extends Mailable
{
    use Queueable, SerializesModels;

    private $request;
    private $shop_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $shop_info)
    {

        $this->request = $request;
        $this->shop_info = $shop_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Helpers::saveLog('info', ['message' => 'Send mail new reviews']);
        $data = $this->request;
        $shop_info = $this->shop_info;
        $this->from('support@fireapps.io', 'AliReviews');
        return $this->subject('You got a new comment from your customer! - Ali Reviews')->view('emails.template.frontendreview', ['data' => $data, 'shop_info' => $shop_info]);
    }
}
