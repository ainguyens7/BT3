<?php

namespace App\Mail;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FreeToUnlimited extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $endDate;
    private $appURL;
    protected $theme2 = 'free_to_unlimitted';
    /**
     * Create a new message instance.
     *
     */
    public function __construct($data, $endDate, $appURL)
    {
        $this->data = $data;
        $this->endDate = $endDate;
        $this->appURL = $appURL;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('support@fireapps.io', 'Alireviews');
        return $this->subject("Get your 7-day free trial for Unlimited Plan - Alireviews")->view('emails.template.FreeToUnlimited',
            ['data' => $this->data, 'endDate' => $this->endDate, 'appURL' => $this->appURL]);
    }
}
