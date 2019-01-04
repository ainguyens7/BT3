<?php

namespace App\Mail;

use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Feedback extends Mailable
{
	use Queueable, SerializesModels;

	private $request;

	/**
	 * Create a new message instance.
	 *
	 */
	public function __construct($request)
	{

		$this->request = $request;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		Helpers::saveLog('info', ['message' => 'Send mail feedback']);

		$data = $this->request;
		$this->from('support@fireapps.io', 'AliReviews');
		return $this->subject('New Feedback From Shopify Store - AliReviews')->view('emails.template.feedback', ['data' => $data]);
	}
}
