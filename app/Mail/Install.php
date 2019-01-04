<?php

namespace App\Mail;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Install extends Mailable
{
	use Queueable, SerializesModels;

	private $data;

	/**
	 * Create a new message instance.
	 *
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
			$this->from('support@fireapps.io', 'AliReviews');
			return $this->subject("Let's get social proof to your store & increase conversion! - Ali Reviews")->view('emails.template.installapp', ['data' => $this->data]);
	}
}
