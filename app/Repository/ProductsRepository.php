<?php

namespace App\Repository;

use App\Helpers\Helpers;
use App\Jobs\ImportProductsFromApi;
use App\Models\ProductsModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

/**
 * Class ProductsRepository
 * @package App\Repository
 */
class ProductsRepository {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_productModel;
	private $_commentBackendRepo;

	/**
	 * Sentry service
	 */
	protected $sentry;

	/**
	 * ProductsRepository constructor.
	 */
	function __construct() {
		$this->_productModel       = new ProductsModel();
		$this->_commentBackendRepo = app( CommentBackEndRepository::class );
		$this->sentry = app('sentry');
	}

	/**
	 * @param $shopId
	 *
	 * @return string
	 */
	public function getTableProduct( $shopId ) {
		$table = $this->_productModel->getTable() . $shopId;
		if ( ! Schema::connection( $this->_productModel->getConnectionName() )->hasTable( $table ) ) {
			$this->createTable( $table );
		}

		return $table;
	}

	/**
	 * Add columnName to table products_shopId
	 * @param String $shopId
	 * @param String $columnName
	 */

	public function addColumn($shopId = '', $columnName = '')
	{
		$client = new \Raven_Client( env( 'SENTRY_DSN' ) );
		$client->user_context([
			'shopId' => $shopId,
			'columnName' => $columnName
		]);

		if (!$shopId || !$columnName) {
			return false;
		}
		try {
			$schemaConnection = Schema::connection($this->_productModel->getConnectionName());

			$tableName = $this->_productModel->getTable() . $shopId;

			if (!$schemaConnection->hasTable($tableName))
			{
				return false;
			}

			if ($schemaConnection->hasColumn($tableName, $columnName)) {
				return true;
			}
			$result = $schemaConnection->table($tableName, function($table) use (&$columnName)
			{
				$table->longText($columnName)->nullable();
			});
			return $result;
		} catch (\Exception $ex) {
			$client->captureException($ex);
			return false;
		}
	}

	/**
	 * @param $table
	 *
	 * @return bool
	 */
	public function createTable( $table ) {
		if ( Schema::connection( $this->_productModel->getConnectionName() )->hasTable( $table ) ) {
			return false;
		}
		Schema::connection( $this->_productModel->getConnectionName() )->create( $table, function ( Blueprint $table ) {
			$table->string( 'id' );
			$table->string( 'title', 1000 );
			$table->string( 'handle', 1000 );
			$table->string( 'image', 1000 )->nullable();
			$table->string( 'collection_id' )->nullable();
			// added in v3.3
			$table->longText('review_link')->nullable();
			$table->integer( 'is_reviews' )->default( 0 );
			$table->timestamps();
			$table->primary( 'id' );
			$table->index( 'id' );
		} );
	}

	/**
	 * Get list products
	 *
	 * @param $shopId
	 * @param array $filter
	 * @param array $orderBy
	 *
	 * @return array
	 */
	public function getAll( $shopId, $filter = [], $orderBy = [] ) {
		try {
			$products = DB::connection( $this->_productModel->getConnectionName() )->table( $this->getTableProduct( $shopId ) );

			if ( isset( $filter['currentPage'] ) ) {
				$currentPage = $filter['currentPage'];
				Paginator::currentPageResolver( function () use ( $currentPage ) {
					return $currentPage;
				} );
			}

			if ( ! empty( $filter['title'] ) ) {
				$products->where( 'title', 'like', '%' . $filter['title'] . '%' );
			}
			if ( ! empty( $filter['colletion_id'] ) ) {
				$products->where( 'collection_id', $filter['colletion_id'] );
			}
			if ( isset( $filter['is_review'] ) ) {
				if ( $filter['is_review'] == - 1 ) {
					$products->where( 'is_reviews', 0 );
				} else {
					$products->where( 'is_reviews', '>', 0 );
				}
			}
			$products->orderBy( 'created_at', 'DESC' );
			$listProducts = $products->paginate( config( 'common.pagination' ) );

			if ( isset( $filter['currentPage'] ) ) {
				Paginator::currentPageResolver( function () {
					return 1;
				} );
			}

			return [ 'status' => true, 'products' => $listProducts ];
		} catch ( \Exception $exception ) {
			return [ 'status' => false, 'message' => $exception->getMessage() ];
		}
	}

	public function getAllProductSupportWorker($shopId)
	{
		try {
			$table = DB::connection( $this->_productModel->getConnectionName() )->table( $this->getTableProduct( $shopId ) );
			$query = $table->select('id')->get();
			return [ 'status' => true, 'products' => $query ];
		} catch (\Exception $ex) {
			$this->sentry->captureException($ex);
			return [ 'status' => false, 'message' => $exception->getMessage() ];
		}
	}

	/**
	 * Get detail product
	 *
	 * @param $shopId
	 * @param $productId
	 *
	 * @return mixed
	 */
	public function detail($shopId = '', $productId = '')
	{
		$this->sentry->user_context([
			'shopId' => $shopId,
			'productId' => $productId
		]);

		try {
			$connection = $this->_productModel->getConnectionName();
			$prodTableName = $this->getTableProduct($shopId);
			$product = DB::connection($connection)
						->table($prodTableName)
					 	->where('id', $productId)
						 ->first();
						 
			return $product;			
		} catch (Exception $ex) {
			$this->sentry->captureException($ex);
			return NULL;
		}
	}

	public function bulkDetail($shopId, $productList = [])
	{
		$this->sentry->user_context([
			'shopId' => $shopId,
			'productList' => $productList
		]);

		try {
			$connection = $this->_productModel->getConnectionName();
			$prodTableName = $this->getTableProduct($shopId);
			$product = DB::connection($connection)
						->table($prodTableName)
						->select('id', 'is_reviews')
					 	->whereIn('id', $productList)->get();
						 
			return $product;			
		} catch (Exception $ex) {
			$this->sentry->captureException($ex);
			return NULL;
		}
	}

	public function countReviewProduct($shopId,$source = 'all', $cache = true) {
//		$numberProductReview = DB::connection( $this->_productModel->getConnectionName() )
//			->table( $this->getTableProduct( $shopId ) )
//			->where('review_link', '!=', null)
//			->where('is_reviews', 1)
//			->count();
//		return $numberProductReview;

		if (Cache::has('countReviewProduct_'.$shopId.$source) && $cache) {
			return (Cache::get('countReviewProduct_'.$shopId.$source));
		}
		$table = $this->_commentBackendRepo->getTableComment($shopId);

		switch ($source) {
			case 'web':
				$query = 'SELECT product_id AS total FROM '.$table .' WHERE source IN("web") GROUP BY product_id';
				break;
			case 'import':
				$query = 'SELECT product_id AS total FROM '.$table .' WHERE source IN("aliexpress", "oberlo","aliorder") GROUP BY product_id';
				break;
			case 'all':
				$query = 'SELECT product_id AS total FROM '.$table .' WHERE source IN("aliexpress", "oberlo","aliorder","web") GROUP BY product_id';
				break;
		}

		$result = DB::select($query);
		if(!empty($result)){
			$total = count($result);
			Cache::put('countReviewProduct_'.$shopId.$source,$total , 60*60*24);
			return $total;
		}else{
			return 0;
		}
	}

	public function getListProductsImportedReviews($shopId, $checkPublish = true){
        $this->sentry->user_context([
            'shopId' => $shopId,
        ]);
        try {
            $where = '';
            if($checkPublish){
                $where = ' AND status = 1 ';
            }

            $table = $this->_commentBackendRepo->getTableComment($shopId);
            $query = 'SELECT product_id  FROM '.$table .' WHERE source IN("aliexpress", "oberlo","aliorder") '.$where.' GROUP BY product_id';
            $result = DB::select($query);
            $list = [];
            if(!empty($result)){
                foreach ($result as $v){
                    $list[] = $v->product_id;
                }
            }
            return $list;
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
            return [];
        }
    }

	/**
	 * Count total products
	 *
	 * @param $shopId
	 * @param $filter
	 *
	 * @return mixed
	 */
	public function countProduct( $shopId, $filter ) {
		$products = DB::connection( $this->_productModel->getConnectionName() )->table( $this->getTableProduct( $shopId ) );
		if ( ! empty( $filter['title'] ) ) {
			$products->where( 'title', 'like', '%' . $filter['title'] . '%' );
		}
		if ( ! empty( $filter['colletion_id'] ) ) {
			$products->where( 'collection_id', $filter['colletion_id'] );
		}
		if ( isset( $filter['is_review'] ) ) {
			if ( $filter['is_review'] == - 1 ) {
				$products->where( 'is_reviews', 0 );
			} else {
				$products->where( 'is_reviews', '>', 0 );
			}
		}
		$countProduct = $products->count();

		return $countProduct;
	}

	/**
	 * Update product
	 *
	 * @param $shopId
	 * @param $productId
	 * @param $data
	 *
	 * @return bool
	 */
	public function update( $shopId, $productId, $data ) {
		$client = new \Raven_Client( env( 'SENTRY_DSN' ) );
		$client->user_context( array(
			'shop_id'   => $shopId,
			'productId' => $productId,
		) );
		try {
			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$dbProducts  = $openConnect->table( $this->getTableProduct( $shopId ) );


			if ( $dbProducts->where( 'id', $productId )->count() > 0 ) {
				$product_save = $this->filterProductDatabase( $data );

				if ( $dbProducts->where( 'id', $productId )->update( $product_save ) ) {
					return true;
				}
			}

			return false;
		} catch ( \Exception $exception ) {
			$client->captureException( $exception );

			return false;
		}
	}

	/**
	 * @param $shopId
	 *
	 * @return bool
	 */
	public function updateProductByShop( $shopId ) {
		$client = new \Raven_Client( env( 'SENTRY_DSN' ) );
		$client->user_context( array(
			'shop_id' => $shopId,
		) );

		try {
			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$dbProducts  = $openConnect->table( $this->getTableProduct( $shopId ) );
			$update      = $dbProducts->update( [ 'is_reviews' => 0 ] );
			if ( $update ) {
				return true;
			}

			return false;
		} catch ( \Exception $exception ) {
			$client->captureException( $exception );

			return false;
		}
	}

	/**
	 * @param $shopId
	 * @param $data
	 *
	 * @return bool
	 */
	public function create( $shopId, $data ) {
		$this->sentry->user_context( array(
			'shop_id' => $shopId,
		) );

		try {
			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$dbProducts  = $openConnect->table( $this->getTableProduct( $shopId ) );

			$data_save   = array(
				'id'         => $data['id'],
				'title'      => $data['title'],
				'handle'     => $data['handle'],
				'image'      => ! empty( $data['image']['src'] ) ? $data['image']['src'] : '',
				'updated_at' => date( 'y-m-d H:i:s', time() ),
				'created_at' => date( 'y-m-d H:i:s', strtotime( $data['created_at'] ) ),
			);

			if($dbProducts->where( 'id', $data_save['id'] )->count() > 0){
				unset($data_save['id']);
				if ( $dbProducts->where( 'id', $data['id'] )->update( $data_save ) ) {
					return true;
				}
			}else{
				$create = $dbProducts->insert( $data_save );

				if ( ( $create ) ) {
					return true;
				}
			}

			return false;
		} catch (Exception $exception ) {
			$this->sentry->captureException($exception);
			return false;
		}
	}

	/**
	 * Import product from api and webhook update-add product in shopify
	 *
	 * @param $shopId
	 * @param $products
	 * @param $accessToken
	 * @param $shopDomain
	 *
	 * @return bool
	 */
	public function import( $shopId, $products ) {
		foreach ( $products as $key => $product ) {
			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$dbProducts  = $openConnect->table( $this->getTableProduct( $shopId ) );

			if ( is_array( $product ) ) {
				$product['image']      = isset( $product['image']['src'] ) ? $product['image']['src'] : null;
				$product['created_at'] = isset( $product['created_at'] ) ? date( 'Y-m-d H:i:s', strtotime( $product['created_at'] ) ) : date( 'Y-m-d H:i:s' );
				$product['updated_at'] = isset( $product['updated_at'] ) ? date( 'Y-m-d H:i:s', strtotime( $product['updated_at'] ) ) : date( 'Y-m-d H:i:s' );
			} else {
				$product->image      = isset( $product->image->src ) ? $product->image->src : null;
				$product->created_at = isset( $product->created_at ) ? date( 'Y-m-d H:i:s', strtotime( $product->created_at ) ) : date( 'Y-m-d H:i:s' );
				$product->updated_at = isset( $product->updated_at ) ? date( 'Y-m-d H:i:s', strtotime( $product->updated_at ) ) : date( 'Y-m-d H:i:s' );
				$product             = get_object_vars( $product );
			}

			$total_review = $this->_commentBackendRepo->getTotalReview( $product['id'], $shopId );
			if ( $total_review ) {
				$product['is_reviews'] = 1;
			}

			$dataSave = $this->filterProductDatabase( $product );
			$productId = $dataSave['id'];
			$productInfo = $dbProducts->where( 'id', $productId )->first();

			$this->sentry->user_context( array(
				'shop_id' => $shopId,
			) );

			if ( empty( $productInfo ) ) {
				try {
					$dbProducts->insert( $dataSave );
				} catch ( \Exception $exception ) {
					$this->sentry->captureException( $exception );
				}
			} else {
				try {
					unset( $dataSave['id'] );
					$dbProducts->where( 'id', $productId )->update( $dataSave );
				} catch ( \Exception $exception ) {
					$this->sentry->captureException( $exception );
				}
			}
		}

		return true;
	}

	/**
	 * Convert product to product save database
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function filterProductDatabase( $data ) {
		$fillable = $this->_productModel->getFillable();
		foreach ( $data as $k => $v ) {
			if ( ! in_array( $k, $fillable ) ) {
				unset( $data[ $k ] );
			}
		}

		return $data;
	}

	/**
	 * Delete product
	 *
	 * @param $shopId
	 * @param $productId
	 *
	 * @return mixed
	 */
	public function delete( $shopId, $productId ) {
		$client = new \Raven_Client( env( 'SENTRY_DSN' ) );
		$client->user_context( array(
			'shop_id'   => $shopId,
			'productId' => $productId,
		) );

		try {
			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$dbProducts  = $openConnect->table( $this->getTableProduct( $shopId ) );
			$delete      = $dbProducts->where( 'id', $productId )->delete();

			return $delete;
		} catch ( \Exception $exception ) {
			$client->captureException( $exception );

			return false;
		}
	}


	public function updateIsReviewsAllProducts($shopId){
		$client = new \Raven_Client( env( 'SENTRY_DSN' ) );
		$client->user_context( array(
			'shop_id'   => $shopId,
		) );

		try {

			$openConnect = DB::connection( $this->_productModel->getConnectionName() );
			$listProducts  = $openConnect->table( $this->getTableProduct( $shopId ) )->select('id')->get();
			if(!empty($listProducts)){
				foreach ($listProducts as $product){
					$queryComment = DB::table($this->_commentBackendRepo->getTableComment($shopId))->where('product_id',$product->id)->select('id')->first();
					if(empty($queryComment)){
						$this->update($shopId, $product->id, ['is_reviews' => 0]);
					}else{
						$this->update($shopId, $product->id, ['is_reviews' => 1]);
					}
				}
			}

			return TRUE;

		}catch ( \Exception $exception ) {
			$client->captureException( $exception );

			return false;
		}
	}
}
