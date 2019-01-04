<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use App\Jobs\SendMailFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
	protected $_shopRepo;
	protected $_feedbackRepo;

	public function __construct()
	{
		$this->_shopRepo = RepositoryFactory::shopsReposity();
		$this->_feedbackRepo = RepositoryFactory::feedbackRepository();
	}

	public function save(Request $request){
		$result = array(
			'status'  => 'error',
			'message' => 'An error, please try again.',
		);

		$shopInfo = $this->_shopRepo->detail(['shop_id' => session('shopId')]);
		if(($shopInfo['status'])){
			$shopEmail = $shopInfo['shopInfo']->shop_email;
			$data = $request->all();
			if(!empty($data['feedback'])){
				$args_save = array(
					'shop_id' => session('shopId'),
					'feedback' => $data['feedback'],
					'status' => 'pending'
				);

				$insert = $this->_feedbackRepo->insert($args_save);
				if($insert){
					$result = array(
						'status'  => 'success',
						'message' => 'Send feedback successful. Thank you!',
					);

					$data_mail = [
						'shop_email' => $shopEmail,
						'shop_domain' => session('shopDomain'),
						'feedback' => $data['feedback'],
					];
					$this->dispatch(new SendMailFeedback($data_mail));
				}
			}
		}

		echo json_encode($result); exit();
	}
}
