<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Repository\ProductsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateProductFromWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 3;

	/**
	 * The number of seconds the job can run before timing out.
	 *
	 * @var int
	 */
	public $timeout = 120;

	private $_shopId;

	private $_data;

	/**
	 * @var
	 */
	private $_productRepo;


	/**
	 * updateProductFromWebhook constructor.
	 *
	 * @param $shopId
	 * @param $data
	 */
	public function __construct($shopId,$data)
	{
		$this->_shopId = $shopId;

		$this->_data = $data;

		$this->_productRepo = new ProductsRepository();
	}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$data = $this->_data;
    	$shopId = $this->_shopId;

    	$product_id = $data['id'];

    	$data_save = array(
		    'title' => $data['title'],
		    'handle'  => $data['handle'],
		    'image'  => !empty($data['image']['src']) ? $data['image']['src'] : '',
		    'updated_at'  => date('y-m-d H:i:s',strtotime($data['updated_at'])),
	    );

	    $update = $this->_productRepo->update($shopId,$product_id,$data_save);
	    if(!$update){
		    Helpers::saveLog('error', ['message' => 'Webhook Update Product '.$product_id.' ERROR ']);
		    return false;
	    }else{
	    	return true;
	    }
    }
}
