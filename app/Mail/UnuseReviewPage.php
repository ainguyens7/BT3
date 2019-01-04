<?php

namespace App\Mail;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnuseReviewPage extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $appURL;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($data, $appURL)
    {
        $this->data = $data;
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
        return $this->subject("Make your own testimonials page to establish credibility - Alireviews")->view('emails.template.unusereviewpage',
            ['data' => $this->data, 'appURL' => $this->appURL]);
    }
}
