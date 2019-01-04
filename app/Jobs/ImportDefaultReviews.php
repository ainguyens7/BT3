<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use App\Repository\CommentBackEndRepository;
use App\Repository\CommentsDefaultRepository;
use App\Repository\ProductsRepository;
use App\ShopifyApi\ProductsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ImportDefaultReviews implements ShouldQueue
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
    public $timeout = 1200;

    private $_shopId;
    /**
     * @var
     */
    private $_productRepo;
    private $_commentBackendRepo;
    private $_commentDefaultRepo;




    /**
     * ImportProductsFromApi constructor.
     * @param $shopId
     * @param $accessToken
     * @param $shopDomain
     */
    public function __construct($shopId)
    {
        $this->_shopId = $shopId;
        $this->_productRepo = new ProductsRepository();
        $this->_commentDefaultRepo = new CommentsDefaultRepository();
        $this->_commentBackendRepo = new CommentBackEndRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    /*$totalProductApi = $this->getCountProductsApi();
        $page = ceil($totalProductApi/$this->_limitApi);
        for($i = 1; $i <= $page; $i++)
        {
            $products = $this->getProductsApi($i);
            if( ! $products)
            {
                Helpers::saveLog('error', ['message' => 'Cannot import product from API', 'file' => __FILE__, 'line' => __LINE__, 'function' => __FUNCTION__, 'domain' => $this->_shopDomain]);
            }

            if( ! $this->saveProduct($products))
            {
                Helpers::saveLog('error', ['message' => 'Cannot save product from API to database', 'file' => __FILE__, 'line' => __LINE__, 'function' => __FUNCTION__, 'domain' => $this->_shopDomain]);
            }

            sleep(5);
        }*/



	    $filter_product = [
		    'is_review' => -1,
		    //'currentPage' => 2,
	    ];
	    $total_products = $this->_productRepo->countProduct($this->_shopId,$filter_product);
	    $page = ceil($total_products /  config('common.pagination'));

	    for($i = 1; $i <= $page; $i++)
	    {
		    // $filter_product['currentPage'] = $i;
		    $listProducts = $this->_productRepo->getAll($this->_shopId,$filter_product);

		    if($listProducts['status'] && !empty($listProducts['products']->total())) {
			    foreach ( $listProducts['products'] as $product ) {
				    $limit = rand( 15, 30 );
				    $list  = $this->_commentDefaultRepo->all( $this->_shopId, [
					    'perPage'   => $limit,
					    'is_random' => 1,
				    ] );
				    echo 'total: '.$list->total().'<br>';

				    if ( ! empty( $list->total() ) ) {
					    $list = $list->toArray();
					    foreach ( $list['data'] as $kd =>  $comment ) {
						    unset( $comment['shop_id'] );
						    unset( $comment['like'] );
						    unset( $comment['unlike'] );
						    unset( $comment['id'] );

						    $comment['source']     = 'default';
						    $comment['product_id'] = $product->id;
						    $comment['email'] = 'anonymous@shopboostify.com';
						    $comment['avatar'] = Helpers::getAvatarAbstract();

						    //fake time
						    $comment['created_at'] = date('Y-m-d H:i:s',time() - 3600 * ($kd + 1));

						    //echo 'sp: '.$product->id;

						    $insert = $this->_commentBackendRepo->insert( $this->_shopId, $comment );
						    if($insert){
							    $this->_productRepo->update($this->_shopId,$product->id,['is_reviews' => 1]);
						    }
					    }
				    }
			    }
		    }

		    sleep(5);
	    }
    }
}
