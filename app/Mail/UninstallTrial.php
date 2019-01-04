<?php

namespace App\Mail;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UninstallTrial extends Mailable
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
			return $this->subject('Trully sorry for any inconvenience that makes you stop using our app! - Ali Reviews')->view('emails.template.uninstallTrial', ['data' => $this->data]);
	}
}
