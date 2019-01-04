<?php

namespace App\Repository;


use App\Contracts\Repository\CommentBackendRepositoryInterface;
use App\Events\AddGoogleRatingEvent;
use App\Events\SaveIntercomEvent;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\DeleteReviewsOfShop;
use App\Models\CommentsModel;
use App\Models\ProductsModel;
use App\ShopifyApi\ProductsApi;
use Faker\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Events\UpdateCache;

/**
 * Class CommentBackEndRepository
 * @package App\Repository
 */
class CommentBackEndRepository implements CommentBackendRepositoryInterface {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_commentModel;


	private $sentry;

	private $_productModel;

    private $_shopRepo ;

    // private $_productRepo;

	/**
	 * CommentBackEndRepository constructor.
	 *
	 */
	public function __construct() {
        $this->_shopRepo = RepositoryFactory::shopsReposity();
        $this->_productModel       = new ProductsModel();
		$this->_commentModel = new CommentsModel();
		$this->sentry = app('sentry');
		 // $this->_productRepo = RepositoryFactory::productsRepository();
	}

	/**
	 * Created table comment
	 *
	 * @param $shopId
	 *
	 * @return bool|string
	 */
	public function createTable( $shopId ) {
		$table = 'comment_' . $shopId;
		if ( Schema::hasTable( $table ) ) {
			return true;
		}

		return $this->_commentModel->createTableComment( $shopId );
	}

	/**
	 * Get table comment
	 *
	 * @param $shopId
	 *
	 * @return string
	 */
	public function getTableComment($shopId){
		return $this->_commentModel->getTableComment($shopId);
	}

	/**
	 * Get list review
	 *
	 * @param $shopId
	 * @param $params
	 *
	 * @return mixed
	 */
	public function all($shopId = '', $params = [])
	{
		try {
			$table = $this->_commentModel->getTableComment($shopId);
			$query = DB::table($table);

			$perPage = isset($params['perPage']) ? $params['perPage'] : config('common.pagination');

			if (!empty($params['product_id'])) {
				$query->where('product_id', '=', $params['product_id']);
			}

			if (!empty($params['keyword'])) {
				$query->where('content', 'like', '%' . trim($params['keyword']) . '%');
			}

			if (!empty($params['status'])) {
				if ($params['status'] == 'publish') {
					$query->where('status', '=', 1);
				} else {
					$query->where('status', '!=', 1);
				}
			}

			if (!empty($params['star'])) {
				if (is_array($params['star'])) {
					$query->whereIn('star', $params['star']);
				} else {
					$query->where('star', '=', $params['star']);
				}
			}

			if (!empty($params['source'])) {
				if (is_array($params['source'])) {
					$query->whereIn('source', $params['source']);
				} else if ($params['source'] === 'all') {
					$sourceInternal = array_keys(config('common.review_sources'));
					$query->whereIn('source', $sourceInternal);
				} else {
					$query->where('source', '=', $params['source']);
				}
			}

			if (!empty($params['from'])) {
				$query->whereDate('created_at', '>=', $params['from']);
			}
			if (!empty($params['to'])) {
				$query->whereDate('created_at', '<=', $params['to']);
			}

			if (Schema::hasColumn($table, 'pin')) {
				$query->orderBy('pin', 'DESC');
			}

			$query->orderBy('content', 'desc');

			$total = $query->count();

			if (!is_numeric($perPage) && !empty($total)) {
				$result = $query->orderBy('created_at', 'desc')->paginate($total);
			} else {
				$result = $query->orderBy('created_at', 'desc')->paginate($perPage);
			}

			if ($result->total()) {
				foreach ($result as $comment) {
					if (!empty($comment->img)) {
						$comment->img = json_decode($comment->img);
					}

					if (!empty($comment->product_id)) {
						$productRepo = new ProductsRepository();
						$product = $productRepo->detail($shopId, $comment->product_id);
						$comment->product_info = $product;
					}
				}
			}

			return $result;
		} catch (Exception $ex) {
			$this->sentry->captureException($ex);
			return [];
		}
	}


	/**
	 * Get detail comment
	 *
	 * @param $shopId
	 * @param $commentId
	 *
	 * @return mixed
	 */
	public function detail( $shopId, $commentId ) {
		$comment = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'id', $commentId )->first();
		if ( ! empty( $comment ) ) {
			if ( ! empty( $comment->img ) ) {
				$comment->img = json_decode( $comment->img );
			}
		}

		return $comment;
	}

	public function findReview($shopId = '', $productId = '', $data = [])
	{
		$this->sentry->user_context([
			'shopId' => $shopId,
			'product_id' => $productId,
			'data' => $data
		]);
		try {
			$tableComment = $this->_commentModel->getTableComment($shopId);
			$query = DB::table($tableComment);
			if (!empty($productId)) {
				$query->where('product_id', $productId);
			}
			if (array_key_exists('source', $data)) {
				if (is_array($data['source'])) {
					$query->whereIn('source', $data['source']);
				} else {
					$query->where('source', $data['source']);
				}
			}

			if (array_key_exists('status', $data)) {
				$query->where('status', $data['status']);
			}
			if (array_key_exists('limit', $data)) {
				$limit = (int) $data['limit'];
				$query->take($limit);
			}
			return ['status' => true, 'result' => $query];
		} catch (Exception $ex) {
			$eventId = $this->sentry->captureException($ex);
			return ['status' => false, 'message'=> "{$ex->getMessage()}. EventId: {$eventId}"];
		}
	}


	/**
	 * Get average ratting
	 *
	 * @param $productId
	 * @param $shopId
	 *
	 * @return float
	 */
	public function getAvgStar( $productId, $shopId, $status = 0 ) {
		$avg = DB::table( $this->_commentModel->getTableComment( $shopId ) )
		         ->where( 'product_id', '=', $productId );
		if(!empty($status)){
			$avg->where( 'status', '=',config('common.status.publish') );
		}

		return round( $avg->avg( 'star' ),1 );
	}


	/**
	 * Get total review by star
	 *
	 * @param $productId
	 * @param $shopId
	 * @param null $star
	 * @param array $status
	 *
	 * @return mixed
	 */
	public function getTotalStar( $productId, $shopId, $star = null, $status = [1] ) {
		$query = DB::table( $this->_commentModel->getTableComment( $shopId ) )
		           ->where( 'product_id', '=', $productId );
		if ( ! empty( $status ) ) {
			$query->whereIn( 'status', $status );
		}
		if ( isset( $star ) ) {
			$query->where( 'star', '=', $star );
		}

		$total = $query->count( 'star' );

		return $total;
	}


	/**
	 * Get total review by status
	 *
	 * @param $productId
	 * @param $shopId
	 * @param null $status
	 *
	 * @return mixed
	 */
	public function getTotalStatus( $productId, $shopId, $status = null ) {
		$query = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'product_id', '=', $productId );
		if ( isset( $status ) ) {
			$query->where( 'status', '=', $status );
		}

		$total = $query->count( 'status' );

		return $total;
	}

	public function getTotalReview($productId, $shopId)
	{
		$totalReview = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'product_id', '=', $productId )->count('id');
		return $totalReview;
	}

	/**
	 * Convert data to save comment
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function convertDataSave( $data ) {
		$data['updated_at'] = date( 'Y-m-d H:i:s', time() );
		if(!empty($data['img'])){
			$data['img'] = json_encode($data['img']);
		}

		/*if(empty($data['avatar'])){
			$data['avatar'] = Helpers::getAvatarAbstract();
		}*/

		return $data;
	}


	/**
	 * Insert comment
	 *
	 * @param $shopId
	 * @param $data
	 *
	 * @return array
	 */
	public function insert($shopId, $data ){
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);

		$data = $this->convertDataSave( $data );
		$update = DB::table( $this->_commentModel->getTableComment( $shopId ) )->insert( $data );
		if ( $update ) {
			$result = array(
				'status'  => 'success',
				'message' => Lang::get( 'reviews.updateSuccess' ),
			);
		}

		return $result;
	}

	/**
	 * Update comment
	 *
	 * @param $shopId
	 * @param $commentId
	 * @param $data
	 *
	 * @return array
	 */
	public function update($shopId = '', $commentId = '', $data = [])
	{
		$this->sentry->user_context([
			'shop_id' => $shopId,
			'comment_id' => $commentId,
			'data' => $data
		]);
		try {
			$result = [
				'status'  => 'error',
				'message' => trans('reviews.fail'),
			];

			if (!empty($commentId)) {
				$data = $this->convertDataSave($data);

				$update = DB::table($this->_commentModel->getTableComment($shopId))->where('id', $commentId)->update($data);

				if ($update) {
					// clear cache
					$comment = $this->detail($shopId, $commentId);
					if ($comment) {
						event(new UpdateCache($shopId, $comment->product_id));
					}
					$result = [
						'status'  => 'success',
						'message' => trans('reviews.updateSuccess'),
					];
				}
			}

			return $result;
		} catch (Exception $ex) {
			$eventId = $this->sentry->captureException($ex);
			return [
				'stauts' => 'error',
				'message' => "{$ex->getMessage()}. EventId: {$eventId}"
			];
		}
	}

	/**
	 * Delete comment
	 *
	 * @param $shopId
	 * @param $commentId
	 *
	 * @return array
	 */
	public function delete( $shopId, $commentId ): array {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);

		if ( ! empty( $commentId ) ) {
			$comment = $this->detail($shopId,$commentId);
			if($comment){
				$delete = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'id', $commentId )->delete();

				if ( $delete ) {
                    # Fire event clear cache
                    event(new UpdateCache($shopId, $comment->product_id));

					/**
					 *  echo if is last comment to change is_reviews of product to 0
					 */
					$productRepo = RepositoryFactory::productsRepository();
					$list_product_comments = $this->all($shopId,['product_id' => $comment->product_id]);
					if($list_product_comments->total() == 0){
						$productRepo->update($shopId,$comment->product_id,['is_reviews' => 0]);
					}

					$result = array(
						'status'  => 'success',
						'message' => Lang::get( 'reviews.deleteSuccess' ),
					);
				}
			}
		}

		return $result;
	}

	public function deleteComments($shopId = '', $comment = [], $urlParam = [])
	{

		$this->sentry->user_context([
			'shop_id' => $shopId,
			'comments' => $comment
		]);

		try {
			$result = [
				'status' => 'error',
				'message' => trans('reviews.fail')
			];
			DB::beginTransaction();

			$tableComment = DB::table($this->_commentModel->getTableComment($shopId));

			if (!empty($comment)) {
                $commentObject  = $tableComment->whereIn('id', $comment)
                                ->groupBy('product_id')
                                ->get(['product_id'])
                                ->pluck('product_id')->toArray();
				$query = $tableComment->whereIn('id', $comment);
                if (isset($urlParam['status']) && !empty($urlParam['status'])) {
                    $status = ($urlParam['status']  === 'publish') ? '1' : '0';
                    $query->where('status', $status) ;
                }
                if (isset($urlParam['source']) && !empty($urlParam['source']) && $urlParam['source'] !=='all') {
                    $query->where('source', $urlParam['source']) ;
                }
                if (isset($urlParam['star']) && !empty($urlParam['star'])) {
                    $query->whereIn('star', $urlParam['star']) ;
                }
                if (isset($urlParam['keyword']) && !empty($urlParam['keyword'])) {
                    $query->where('content', 'LIKE', '%' . $urlParam['keyword'] . '%');
                }
                $query->delete();

				if ($query) {
					DB::commit();
                    # Fire event update cache
                    if ($commentObject) {
                        array_map(function ($productId) use ($shopId){
                            event(new UpdateCache($shopId, $productId));
                        },$commentObject) ;
                    }
					return [
						'status' => 'success',
						'message' => trans('reviews.deleteSuccess')
					];
				}
			}
			return $result;
		} catch (Exception $ex) {
			$eventId = $this->sentry->captureException($ex);
			DB::rollback();
			return [
				'status' => 'error',
				'message' => "{$ex->getMessage()}. EventId: {$eventId}"
			];
		}
	}


	public function updateComments($shopId = '', $comment = [], $data = [] ,$urlParam = [])
	{
		$this->sentry->user_context([
			'shop_id' => $shopId,
			'comments' => $comment
		]);

		try {
			$result = [
				'status' => 'error',
				'message' => trans('reviews.fail')
			];

			DB::beginTransaction();

			$tableComment = DB::table($this->_commentModel->getTableComment($shopId));
            $shopInfo  = $this->_shopRepo->detail(['shop_id'=>$shopId]) ;
            if($shopInfo['status']) {
                if (!empty($comment)) {
                    $commentObject = $tableComment->whereIn('id', $comment)
                        ->groupBy('product_id')
                        ->get(['product_id'])
                        ->pluck('product_id')->toArray();
                    $query = $tableComment->whereIn('id', $comment);

                    if (isset($urlParam['status']) && !empty($urlParam['status'])) {
                        $status = ($urlParam['status'] === 'publish') ? '1' : '0';
                        $query->where('status', $status);
                    }

                    if (isset($urlParam['source']) && !empty($urlParam['source']) && $urlParam['source'] !== 'all') {
                        $query->where('source', $urlParam['source']);
                    }

                    if (isset($urlParam['star']) && !empty($urlParam['star'])) {
                        $query->whereIn('star', $urlParam['star']);
                    }

                    if (isset($urlParam['keyword']) && !empty($urlParam['keyword'])) {
                        $query->where('content', 'LIKE', '%' . $urlParam['keyword'] . '%');
                    }
                    if($data['status'] === "1" || $data['status'] === 1){
                        if($shopInfo['shopInfo']){
                            if($shopInfo['shopInfo']->app_plan === 'free'){
                                $query->whereIn('source',['aliexpress', 'aliorder'])->limit(5);
                            }
                        }
                    }
                    $query->update($data);

                    if ($query) {
                        DB::commit();

                        # Fire event update cache
                        if ($commentObject) {
                            array_map(function ($productId) use ($shopId) {
                                event(new UpdateCache($shopId, $productId));
                            }, $commentObject);
                        }

                        return [
                            'status' => 'success',
                            'message' => trans('reviews.updateSuccess')
                        ];
                    }

                }
                return $result;
            }
		} catch (Exception $ex) {
			$eventId = $this->sentry->captureException($ex);
			DB::rollback();
			return [
				'status' => 'error',
				'message' => "{$ex->getMessage()}. EventId: {$eventId}"
			];
		}
	}

	/**
	 * Update all comment in product
	 *
	 * @param $shopId
	 * @param $productId
	 * @param $data
	 *
	 * @return array
	 */
	public function updateAll($shopId,$productId, $data): array {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);
		$data = $this->convertDataSave( $data );

		$update = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where('product_id', $productId)->update( $data );

		if ( $update ) {
			// Fire event update cache
			event(new UpdateCache($shopId, $productId));
			$result = array(
				'status'  => 'success',
				'message' => Lang::get( 'reviews.updateSuccess' ),
			);
		}

		return $result;
	}

	public function updateAllCommentBySource($shopId = '', $source = '', $data = [])
	{
		$this->sentry->user_context(array(
			'shop_id' => $shopId,
			'source' => $source,
			'data' => $data
		));
		DB::beginTransaction();

		try {

			$query = DB::table($this->_commentModel->getTableComment($shopId));
			if (is_array($source)) {
				$query->whereIn('source', $source);
			} else {
				$query->where('source', $source);
			}

			$update = $query->update($data);
			DB::commit();
			// fire event update cache
			event(new UpdateCache($shopId));
		} catch (Exception $ex) {
			$this->sentry->captureException($ex);
			DB::rollback();
		}
	}

	// public function pipeUpdateAndSetPublish($shopId = '', $source = '', $numberPublish = 10)
	// {
	// 	$this->sentry->user_context(array(
	// 		'shop_id' => $shopId,
	// 		'source' => $source,
	// 		'numberPublish' => $numberPublish
	// 	));

	// 	DB::beginTransaction();

	// 	try {

	// 		$tableComment = $this->_commentModel->getTableComment($shopId);
	// 		// first set all reviews to disable
	// 		$updateDisable = DB::table($tableComment)
	// 		->where('source', $source)
	// 		->update(['status' => 0]);

	// 		// second update all reviews to active limit by numberPublish
	// 		$updateActive = DB::table($tableComment)
	// 		->where('source', $source)
	// 		->take($numberPublish)
	// 		->update(['status' => 1]);
	// 		DB::commit();
	// 	} catch (Exception $ex) {
	// 		$this->sentry->captureException($ex);
	// 		DB::rollback();
	// 	}
	// }

	// public function updateCommentLimit($shopId, $source, $limit = 1, $status = 1)
	// {
	// 	$this->sentry->user_context(array(
	// 		'shop_id' => $shopId,
	// 		'source' => $source,
	// 		'limit' => $limit
	// 	));

	// 	DB::beginTransaction();

	// 	try {

	// 		$tableComment = $this->_commentModel->getTableComment($shopId);
	// 		$affected = DB::update("update {$tableComment} set status = ? where source = ? limit ?", [$status, $source, $limit]);
	// 		DB::commit();
	// 	} catch (Exception $ex) {
	// 		$this->sentry->captureException($ex);
	// 		DB::rollback();
	// 	}
	// }


	/**
	 * Delete all review in product
	 *
	 * @param $shopId
	 * @param $productId
	 *
	 * @return array
	 */
	public function deleteAll( $shopId,$productId ): array {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);

		$delete = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where('product_id', $productId)->delete();

		if ( $delete ) {
			$productRepo = RepositoryFactory::productsRepository();
			$productRepo->update($shopId,$productId,['is_reviews' => 0]);
			// fire event update cache
			event(new UpdateCache($shopId, $productId));
			$result = array(
				'status'  => 'success',
				'message' => Lang::get( 'reviews.deleteSuccess' ),
			);
		}

		return $result;
	}

	public function delReviewByProductList( $shopId,$productIdList = [] ) {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);

        $delete = DB::table( $this->_commentModel->getTableComment( $shopId ) )->whereIn('product_id', $productIdList)->delete();

		if ( $delete ) {
			foreach($productIdList as $productId) {
				event(new UpdateCache($shopId, $productId));
				$productRepo = RepositoryFactory::productsRepository();
				$productRepo->update($shopId,$productId,['is_reviews' => 0]);
			}


			$result = array(
				'status'  => 'success',
				'message' => Lang::get( 'reviews.deleteSuccess' ),
			);
		}

		return $result;
	}

	/**
	 * @param $shop_id
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function deleteCommentByProduct($shop_id, $product_id)
	{
		$table = $this->_commentModel->getTableComment($shop_id);
		$is_del = DB::table($table)->where('product_id', $product_id)->delete();
		event(new UpdateCache($shop_id, $product_id));
		return $is_del;
	}

	/**
	 * Delete comments by source
	 * @param String $shopId
	 * @param String $source
	 * @return boolean
	 */

	public function deleteCommentBySource($shopId = '', $source = 'default')
	{
		$client =new \Raven_Client(env('SENTRY_DSN'));
		$client->user_context(array(
			'shop_id' => $shopId,
		));
		DB::beginTransaction();
		try {
			$table = $this->_commentModel->getTableComment($shopId);
			if(is_array($source)){
				$query = DB::table($table)->whereIn('source', $source);
				$comments = DB::table($table)->whereIn('source', $source)->distinct('product_id')->get();
			}else{
				$query = DB::table($table)->where('source', $source);
				$comments = DB::table($table)->where('source', $source)->distinct('product_id')->get();
			}
			$productRepo = RepositoryFactory::productsRepository();
			foreach($comments as $comment) {
				event(new UpdateCache($shopId, $comment->product_id));
				$productRepo->update($shopId, $comment->product_id, ['is_reviews' => 0]);
			}
			$query->delete();
			DB::commit();
			return true;
		} catch (\Exception $ex) {
			$client->captureException($ex);
			DB::rollback();
			return false;
		}
	}


	public function deleteCommentBySourceV2($shopId, $action){
		$client =new \Raven_Client(env('SENTRY_DSN'));
		$client->user_context(array(
			'shop_id' => $shopId,
		));
		try{
			$source = '';
			$source_code = '';
			switch ($action) {
				case 'customer':
					$source = 'web';
					$source_code ='customer';
					break;
				case 'imported':
					$source = ['aliexpress','oberlo','aliorder'];
					$source_code ='ali';
					break;
				case "all":
					$source = ['aliexpress','oberlo','web','default','aliorder'];
					break;
			}

			$total = $this->countTotalReviewImported($shopId,$source_code);

			if (Cache::has('deletingReviews_'.$shopId) && !!empty(Cache::get('deletingReviews_'.$shopId))) {
				$result = [
					'status' => trans('api.status', ['status' => 'error']),
					'message' => Lang::get( 'reviews.deleteWaitingError' ),
				];
			} else {
				if($total <= 0){
					$result = [
						'status' => trans('api.status', ['status' => 'error']),
						'message' => Lang::get( 'reviews.emptyReviewsDelete' ),
					];
				}else{
					$result = [
						'status' => trans('api.status', ['status' => 'success']),
						'message' => Lang::get( 'reviews.deleteWaiting' ),
					];

					dispatch(new DeleteReviewsOfShop($shopId,1,$total,$source));
				}
			}
		}catch (\Exception $ex){
			$client->captureException($ex);

			$result = array(
				'status'  => trans('api.status', ['status' => 'error']),
				'message' => Lang::get( 'reviews.fail' ),
			);
		}

		return $result;
	}


	/**
	 * @param $shop_id
	 *
	 * @return bool
	 */
	public function deleteCommentByShop($shop_id)
	{
		$table = $this->_commentModel->getTableComment($shop_id);
		$is_del = DB::table($table)->delete();
		return $is_del;
	}


	/**
	 * @param $shopId
	 * @param $productId
	 * @param $reviewObj
	 * @param $type
	 * @return bool
	 */
	public function saveObjReviewAliexpress($shopId = '', $productId = '', $reviewObj, $type ='', $shopPlanInfo = array(), $review_source = 'aliexpress')
	{

		$this->sentry->user_context(array(
			'shop_id' => $shopId,
			'type' => $type,
			'product_id' => $productId,
			'review_source' => $review_source,
			'shop_plan_info' => $shopPlanInfo,
			'review_obj' => $reviewObj
		));
        try {
            $tableName = $this->_commentModel->getTableComment($shopId);
            $data = [];

            $settings = Helpers::getSettings($shopId);

            if (empty($reviewObj)) {
                return false;
            }

            $total_reviews_publish_product = -1;
            if (!empty($shopPlanInfo['total_reviews_publish_product'])) {
              $total_reviews_publish_product = $shopPlanInfo['total_reviews_publish_product'];

              // nếu không xóa hết review cũ thì cần kiểm tra số review đã publish trước để tính đc số review đc phép publish còn lại
              if ($type != 'del_add_new') {
        //				$list_review_publish = $this->all( $shopId, [
        //					'product_id' => $productId,
        //					'status'     => 'publish',
        //					'source'     => ['aliexpress', 'oberlo', 'aliorder'],
        //				] );

                $queryCount = 'SELECT COUNT(id) AS total FROM '.$this->_commentModel->getTableComment($shopId) .
                              ' WHERE product_id = '.$productId .' AND status = "publish" AND source IN ("aliexpress","oberlo","aliorder") ';
                $resultCount = DB::select($queryCount);

                if(!empty($resultCount)){
                  $total_list_review_publish =  $resultCount[0]->total;
                }else{
                  $total_list_review_publish = 0;
                }

                if (!empty($total_list_review_publish)) {
                  if ($total_list_review_publish >= $total_reviews_publish_product) {
                    $total_reviews_publish_product = 0;
                  } else {
                    $total_reviews_publish_product = $total_reviews_publish_product - $total_list_review_publish;
                  }
                }
              }
            }

      // get list localization
            $listLocalizations = [];
            if(!empty($settings['setting']['is_local_name'])){
                $dir = new \DirectoryIterator(base_path() . '/vendor'. '/fzaninotto/faker/src/Faker/Provider/' );
                foreach ($dir as $fileinfo) {
                    if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                        $localization = $fileinfo->getFilename();
                        $localizationArg = explode('_',$localization);
                        if(!empty($localizationArg[1])){
                            $localizationCode = $localizationArg[1];
                            $listLocalizations[$localizationCode] = $localization;
                        }
                    }
                }
            }

            // percentage of men and women
            $genders_array = [];
            $male_percent = array_key_exists('male_name_percent',$settings['setting'])  ? intval($settings['setting']['male_name_percent']) : config('settings.setting.male_name_percent');
            $total = count($reviewObj);
            $total_male = ROUND($male_percent * $total /100);
            for ($i = 1; $i <= $total ; $i++){
                $gender = 'female';
                if(($i) <= $total_male){
                    $gender = 'male';
                }
                $genders_array[] = $gender;
            }
            shuffle($genders_array);
            $shopInfo = $this->_shopRepo->detail(['shop_id' => $shopId]);

            if(!empty($shopInfo['status'])){
                DB::beginTransaction();

                // delete all review if request was del_add_new
                if ($type == 'del_add_new') {
                    DB::table($tableName)->where('product_id', '=', $productId)->whereIn('source', config('review_source.source'))->delete();
                }

                $shopInfo = $shopInfo['shopInfo'];
                $status = config('common.status.publish');
                $productRepo = RepositoryFactory::productsRepository();
                if (!empty($shopPlanInfo['total_product'])) {
                    $listProductsImportedReviews = $productRepo->getListProductsImportedReviews($shopId);
                    $countProductImportedReview = count($listProductsImportedReviews);
//                    $countProductImportedReview = $productRepo->countReviewProduct($shopId,'import',false);
                    if(($countProductImportedReview >= $shopPlanInfo['total_product']) && !in_array($productId,$listProductsImportedReviews)) {
                        $status = config('common.status.unpublish');
                    }
                }

                foreach ($reviewObj as $k => $v) {
                    if (is_object($v)) {
                        $v= (array) $v;
                    }

                    if(!empty($v['user_country'])){
                        $user_country = $v['user_country'];
                    }else{
                        $user_country = 'US';
                    }

                    if(!empty($listLocalizations[$user_country])){
                        $faker = Factory::create($listLocalizations[$user_country]);
                    }else{
                        $faker = Factory::create();
                    }

                    $gender = $genders_array[$k];

                    if ($total_reviews_publish_product != -1) {
                        if ((($k + 1) > $total_reviews_publish_product)) {
                            $status = config('common.status.unpublish');
                        }
                    }

                    $data[] = [
                        'product_id' => $productId,
                        'author' => $faker->firstName($gender) . ' ' . $faker->lastName,
                        'avatar' => Helpers::getAvatarAbstract(),
                        'email' => /*$faker->email*/ '',
                        'country' => $user_country,
                        'star' => isset($v['review_star']) ? $v['review_star'] : 5,
                        'content' => isset($v['review_content']) ? $v['review_content'] : '',
                        'img' => ( ! empty($v['review_image_str'])) ? json_encode($v['review_image_str']) :  null,
                        'source' => $review_source,
                        'verified' => config('common.status.publish'),
                        'status' => $status,
                        'created_at' => isset($v['review_date']) ? date('Y-m-d H:i:s', strtotime($v['review_date'])) : date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                }
                DB::table($tableName)->insert($data);

                DB::commit();

                # Fire updatereview event
                event(new UpdateCache($shopId, $productId));
                event(new SaveIntercomEvent($shopId,'imported-review'));
                event(new AddGoogleRatingEvent($shopId,$shopInfo->shop_name,$shopInfo->access_token,$productId));
                // if ($shopInfo->app_plan === 'free') {
                //     if ($review_source === 'aliorder' || $review_source === 'aliexpress') {

                //         $this->updateImportReviewFromAliOrder($shopId,$type);

                //     }
                // }

            }


		} catch (\Exception $exception) {
			DB::rollback();
			$this->sentry->captureException($exception);
			return false;
		}

		return true;
	}

	public function addPinColumn($shopId){
		$tableName = $this->_commentModel->getTableComment($shopId);
		if (Schema::hasColumn($tableName, 'pin'))
			return true;

		return Schema::table($tableName, function($table ){
			$table->integer("pin")->default(0);
		});
	}


	public function addLikeColumn($shopId){
		$tableName = $this->_commentModel->getTableComment($shopId);
		if (Schema::hasColumn($tableName, 'like'))
			return true;

		return Schema::table($tableName, function($table ){
			$table->integer("like")->default(0);
		});
	}

	public function addUnLikeColumn($shopId){
		$tableName = $this->_commentModel->getTableComment($shopId);
		if (Schema::hasColumn($tableName, 'unlike'))
			return true;

		return Schema::table($tableName, function($table ){
			$table->integer("unlike")->default(0);
		});
	}

    /**
     * @param $shopId
     * @param $productId
     * @return array
     */
    public function findImportedReview($shopId , $productId){

        $this->sentry->user_context([
            'shopId' => $shopId,
            'product_id' => $productId,
            'source' => config('review_source.source')
        ]);
        try {
            $tableComment = $this->_commentModel->getTableComment($shopId);

            $query = DB::table($tableComment);

            if (!empty($productId)) {

                $query->where('product_id', $productId);

                $query->whereIn('source', config('review_source.source')) ;

                return ['status' => true, 'result' => $query->count()];
            }

        } catch (\Exception $ex) {
            $eventId = $this->sentry->captureException($ex);
            return ['status' => false, 'message'=> "{$ex->getMessage()}. EventId: {$eventId}"];
        }
    }

    /**
     * @param string $shopId
     * @param array $params
     * @param $setting
     * @return array|LengthAwarePaginator
     */
    public function allReviews($shopId = '', $params = [] , $setting)
    {
        try {

            $listReviewImageContentNotNull = [];
            $listReviewContentNull = [];
            $listReviewImageNull = [];
            $listReviewImageContentNull = [];
            $listReviewByLike = [];
            $listReviewByDate = [];
            // get list review with PIN
            $listReviewPin = $this->getReviewsCondition($shopId, $params , $setting , 'pin' ) ;
            if (!empty($setting) && isset($setting['sort_reviews'])) {
                // sort by social
                if (!empty($setting['sort_reviews']) && ($setting['sort_reviews'] === 'sort_by_social')) {

                    // get list review has image and content
                    $listReviewImageContentNotNull = $this->getReviewsCondition($shopId, $params , $setting , 'not-null') ;

                    // get list review content null
                    $listReviewContentNull = $this->getReviewsCondition($shopId, $params , $setting , 'content-null') ;
                    // get list review content null
                    $listReviewImageNull = $this->getReviewsCondition($shopId, $params , $setting , 'img-null') ;

                    // get list review image and content is null
                    $listReviewImageContentNull = $this->getReviewsCondition($shopId, $params , $setting , 'null') ;
                }
                // sort by like
                if (!empty($setting['sort_reviews']) && ($setting['sort_reviews'] === 'sort_by_like')) {
                    // get list sort date
                    $listReviewByLike = $this->getReviewsCondition($shopId, $params , $setting , 'by_like' ) ;

                }
                // sort by date
                if (!empty($setting['sort_reviews']) && ($setting['sort_reviews'] === 'sort_by_date')) {
                    // get list sort date
                    $listReviewByDate = $this->getReviewsCondition($shopId, $params , $setting , 'by_date' ) ;
                }
            }
            // Merge all list condition reviews
//            dd($listReviewImageContentNotNull);
            $items = array_merge($listReviewPin, $listReviewImageContentNotNull, $listReviewContentNull ,$listReviewImageNull ,$listReviewImageContentNull,$listReviewByLike,$listReviewByDate);

            $paginate = config('common.pagination');;

            $perPage = isset($params['page']) ? (int)$params['page'] : 1 ;

            $slice = array_slice($items, $paginate * ($perPage - 1), $paginate);

            $result = (new LengthAwarePaginator($slice , count($items),$paginate ))->setPath(url()->current());

            if ($result->count()) {
                foreach ($result as $comment) {
                    if (!empty($comment->img)) {
                        $comment->img = json_decode($comment->img);
                    }
                    if (!empty($comment->product_id)) {
                        $productRepo = new ProductsRepository();
                        $product = $productRepo->detail($shopId, $comment->product_id);
                        $comment->product_info = $product;
                    }
                }
            }

            return $result;
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
            return [];
        }
    }

    /**
     * @param string $shopId
     * @param array $params
     * @param $setting
     * @param null $option
     * @return array
     */
    private function getReviewsCondition($shopId = '', $params = [] , $setting, $option = null)
    {

        try {

            $table = $this->_commentModel->getTableComment($shopId);
            $query = DB::table($table);
            if (!empty($params['product_id'])) {
                $query->where('product_id', '=', $params['product_id']);
            }
            if (!empty($params['keyword'])) {
                $query->where('content', 'like', '%' . trim($params['keyword']) . '%');
            }
            if (!empty($params['status'])) {
                if ($params['status'] == 'publish') {
                    $query->where('status', '=', 1);
                } else {
                    $query->where('status', '!=', 1);
                }
            }
            if (!empty($params['star'])) {
                if (is_array($params['star'])) {
                    $query->whereIn('star', $params['star']);
                } else {
                    $query->where('star', '=', $params['star']);
                }
            }
            if (!empty($params['source'])) {
                if (is_array($params['source'])) {
                    $query->whereIn('source', $params['source']);
                } else if ($params['source'] === 'all') {
                    $sourceInternal = array_keys(config('common.review_sources'));
                    $query->whereIn('source', $sourceInternal);
                } else {
                    $query->where('source', '=', $params['source']);
                }
            }
            if (!empty($params['from'])) {
                $query->whereDate('created_at', '>=', $params['from']);
            }
            if (!empty($params['to'])) {
                $query->whereDate('created_at', '<=', $params['to']);
            }

            if ($option === 'pin') {
                $query->where('pin', 1)->orderBy('updated_at', 'asc');
            }
            if (!empty($setting)) {

                if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_social') {
                    if ($option === 'not-null') {

                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->where(function ($condition){

                            $condition->where('img', '!=', '')->whereNotNull('img');

                        })->whereNotNull('content')->where('content', '!=', '')->orderBy('created_at', 'desc');
                    }

                    if ($option === 'content-null') {
                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->where(function ($condition){

                            $condition->whereNull('content')->orWhere('content', '');

                        })->whereNotNull('img')->where('img', '!=', '')->orderBy('created_at', 'desc');
                    }

                    if ($option === 'img-null') {

                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->where(function ($condition){
                            $condition->whereNull('img')->orWhere('img','');
                        })->whereNotNull('content')->where('content','!=', '')->orderBy('created_at', 'desc');
                    }

                    if ($option === 'null') {
                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->where(function ($condition){
                            $condition->whereNull('img')->orWhere('img', ' ');
                        })->where(function ($condition){

                            $condition->whereNull('content')->orwhere('content', '');

                        })->orderBy('created_at', 'desc');
                    }
                }

                if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_like'){
                    if($option === 'by_like'){
                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->orderBy('like', 'desc')->orderBy('unlike', 'desc')->orderBy('created_at', 'desc');
                    }
                }

                if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_date'){
                    if ($option === 'by_date') {
                        $query->where(function ($condition){
                            $condition->where('pin', 0)->orWhereNull('pin');
                        })->orderBy('created_at', 'desc');
                    }
                }
            }

            $result = $query->orderBy('created_at', 'desc')->get()->toArray();

            return $result;
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
            return [];
        }
    }


    // count value for intercom
	/**
	 * @param $shopId
	 *
	 * @return int
	 */
	public function countTotalReviewImported($shopId, $source = 'ali')
	{
		switch ($source){
			case "ali" :
				$where = ' WHERE source IN("aliexpress", "oberlo","aliorder") ';
				break;
			case 'customer' :
				$where = ' WHERE source = "web" ';
				break;
			default :
				$where = ' ';
		}

		$query = 'SELECT COUNT(id) AS total FROM '.$this->_commentModel->getTableComment($shopId) .$where;

		$result = DB::select($query);

		if(!empty($result)){
			return $result[0]->total;
		}else{
			return 0;
		}
	}
	// end count value for intercom


    /**
     * @param $shopId
     * @param $productId
     * @param array $unCheckId
     * @param $typePage
     * @param $urlParam
     * @return array
     */
    public function deleteAllCommentReviews($shopId, $productId, $unCheckId = [], $typePage , $urlParam){


        $this->sentry->user_context([
            'shop_id' => $shopId,
            'id_uncheck' => $unCheckId
        ]);
        try {
            $result = [
                'status' => 'error',
                'message' => trans('reviews.fail')
            ];
            DB::beginTransaction();
            $tableComment = DB::table($this->_commentModel->getTableComment($shopId));

            if(!empty($typePage)){
                // delete Review Page Pending Review
                $commentObject = $tableComment->whereNotIn('id', $unCheckId)
                                ->groupBy('product_id')
                                ->get(['product_id'])
                                ->pluck('product_id')->toArray();
                //xóa các id review
                $state  = $tableComment->whereIn('product_id', $commentObject)->whereNotIn('id', $unCheckId)->delete();
            }else{
                // Delete multi page manage review
                $query = $tableComment->where('product_id', $productId)->whereNotIn('id', $unCheckId);
                        if (isset($urlParam['status']) && !empty($urlParam['status'])) {

                            $status = ($urlParam['status']  === 'publish') ? '1' : '0';

                            $query->where('status', $status) ;
                        }
                        if (isset($urlParam['source']) && !empty($urlParam['source']) && $urlParam['source'] !=='all') {
                            $query->where('source', $urlParam['source']) ;
                        }
                        if (isset($urlParam['star']) && !empty($urlParam['star'])) {
                            $query->whereIn('star', $urlParam['star']) ;
                        }
                        if (isset($urlParam['keyword']) && !empty($urlParam['keyword'])) {
                            $query->where('content', 'LIKE', '%' . $urlParam['keyword'] . '%');
                        }

                $query->delete() ;
            }
            if (!empty($typePage)) {
                if($state){
                    DB::commit();
                    # Fire event update cache
                    if ($commentObject) {
                        array_map(function ($idProduct) use ($shopId){
                            event(new UpdateCache($shopId,$idProduct));
                        },$commentObject);
                    }
                    return [
                        'status' => 'success',
                        'message' => trans('reviews.deleteSuccess')
                    ];
                }
            }
            if($query){
                DB::commit();
                # Fire event update cache
                event(new UpdateCache($shopId,$productId));
                return [
                    'status' => 'success',
                    'message' => trans('reviews.deleteSuccess')
                ];
            }
            return $result;
        } catch (Exception $ex) {
            $eventId = $this->sentry->captureException($ex);
            DB::rollback();
            return [
                'status' => 'error',
                'message' => "{$ex->getMessage()}. EventId: {$eventId}"
            ];
        }
    }

    /**
     * @version 4.1.1
     * @author thachleviet
     * @param $shopId
     * @param $productId
     * @param $data
     * @param array $unCheckId
     * @return array
     */
    public function updateAllCommentReviews($shopId, $productId, $data , $unCheckId, $typePage , $urlParam){

        $this->sentry->user_context([
            'shop_id' => $shopId,
            'id_uncheck' => $unCheckId
        ]);

        try {
            $result = [
                'status' => 'error',
                'message' => trans('reviews.fail')
            ];

            DB::beginTransaction();
            $shopInfo  = $this->_shopRepo->detail(['shop_id'=>$shopId]) ;
            if($shopInfo['status']) {


                $tableComment = DB::table($this->_commentModel->getTableComment($shopId));
                if (!empty($typePage)) {
                    $commentObject = $tableComment->whereNotIn('id', $unCheckId)
                        ->groupBy('product_id')
                        ->get(['product_id'])
                        ->pluck('product_id')->toArray();
                    //xóa các id review
                    $state = $tableComment->whereIn('product_id', $commentObject)->whereNotIn('id', $unCheckId)->update($data);
                } else {

                    $query = $tableComment->where('product_id', $productId)->whereNotIn('id', $unCheckId);
                    if (isset($urlParam['status']) && !empty($urlParam['status'])) {
                        $status = ($urlParam['status'] === 'publish') ? '1' : '0';
                        $query->where('status', $status);
                    }
                    if (isset($urlParam['source']) && !empty($urlParam['source']) && $urlParam['source'] !== 'all') {
                        $query->where('source', $urlParam['source']);
                    }
                    if (isset($urlParam['star']) && !empty($urlParam['star'])) {
                        $query->whereIn('star', $urlParam['star']);
                    }
                    if (isset($urlParam['keyword']) && !empty($urlParam['keyword'])) {
                        $query->where('content', 'LIKE', '%' . $urlParam['keyword'] . '%');
                    }
                    if($data['status'] === "1" || $data['status'] === 1) {
                        if ($shopInfo['shopInfo']) {
                            if ($shopInfo['shopInfo']->app_plan === 'free') {
                                $query->whereIn('source', ['aliexpress', 'aliorder'])->limit(5);
                            }
                        }
                    }
                    $query->update($data);
                }

                if (!empty($typePage)) {
                    if ($state) {
                        DB::commit();
                        # Fire event update cache
                        if ($commentObject) {
                            array_map(function ($idProduct) use ($shopId) {
                                event(new UpdateCache($shopId, $idProduct));
                            }, $commentObject);
                        }
                        return [
                            'status' => 'success',
                            'message' => trans('reviews.updateSuccess')
                        ];
                    }
                }
                if ($query) {
                    DB::commit();
                    # Fire event update cache
                    event(new UpdateCache($shopId, $productId));
                    return [
                        'status' => 'success',
                        'message' => trans('reviews.updateSuccess')
                    ];
                }
                return $result;
            }
        } catch (Exception $ex) {
            $eventId = $this->sentry->captureException($ex);
            DB::rollback();
            return [
                'status' => 'error',
                'message' => "{$ex->getMessage()}. EventId: {$eventId}"
            ];
        }

    }

    /**
     * @version 4.1.1
     * @author thachleviet
     * @param $shopId
     * @param $data
     * @param array $uncheckId
     * @return array
     */
    public function updateAllCommentPending($shopId, $data , $uncheckId = []){

        try {
            DB::beginTransaction();
            $data = $this->convertDataSave($data);

            $tableComment  = DB::table($this->_commentModel->getTableComment($shopId));
            $commentObject = $tableComment->whereNotIn('id', $uncheckId)->where(['status'=>'0', 'source'=>'web'])
                                                        ->groupBy('product_id')
                                                        ->get(['product_id'])
                                                        ->pluck('product_id')->toArray();

            if(count($uncheckId) > 0 ){
                $state = $tableComment->whereIn('product_id', $commentObject)->where('source', 'web')->whereNotIn('id', $uncheckId)->update($data);
            }else{
                $state = $tableComment->update($data);
            }
            if($state){
                DB::commit();
                # Fire event update cache
                if ($commentObject) {
                    array_map(function ($idProduct) use ($shopId){
                        event(new UpdateCache($shopId,$idProduct));
                    },$commentObject);
                }
            }
            return [
                'status' => 'success',
                'message' => trans('reviews.updateSuccess')
            ];

        }catch (Exception $ex) {
            $eventId = $this->sentry->captureException($ex);
            DB::rollback();
            return [
                'status' => 'error',
                'message' => "{$ex->getMessage()}. EventId: {$eventId}"
            ];
        }
    }

    public function updateLimitAmountReviewDownPlan($shopId, $productId){

        try{
            DB::beginTransaction();

            $tableComment   = DB::table($this->_commentModel->getTableComment($shopId));

            $result =  $tableComment->where('product_id', $productId)->whereIn('source', ['aliexpress','aliorder'])->limit(5)->update(['status'=>1]);

            DB::commit();

            return $result ;
        }catch (\Exception $exception){

            $this->sentry->captureException($exception);
            DB::rollback();

            return false ;
        }

    }

    public function getListProductReviewsOfPlanFree($shopId){

        try{
            $productRepo      =  new ProductsRepository();

            $products         =  DB::connection( $this->_productModel->getConnectionName() )->table( $productRepo->getTableProduct( $shopId ) );

            $databaseProduct  =  DB::connection( $this->_productModel->getConnectionName() )->getDatabaseName();

            $tableComment     =  DB::table($this->_commentModel->getTableComment($shopId));

            $listProduct      =  $products->orderBy('created_at', 'desc')->get(['id'])->pluck('id')->toArray();

            $tableProducts    =  $products->from ;

            $result           =  $tableComment->distinct()
                                ->join("".$databaseProduct.'.'.$tableProducts." as table_products", 'table_products.id', '=' , $tableComment->from.'.product_id')
                                ->whereIn('source', ['aliexpress','aliorder'])
                                ->whereIn('product_id', $listProduct)
                                ->orderBy('table_products.created_at' , 'desc')
                                ->limit(10)->get(['product_id'])
                                ->pluck('product_id')->toArray();


            if ($result) {

                return ['status'=>true, 'result'=>$result] ;

            }
        }catch (\Exception $exception) {

            $eventId = $this->sentry->captureException($exception);

            return [
                'status' => 'false',
                'message' => "{$exception->getMessage()}. EventId: {$eventId}"
            ];
        }
    }

    public function updateReviewPlanFree($shopId = '', $commentId = '', $data = []){
        $this->sentry->user_context([
            'shop_id' => $shopId,
            'comment_id' => $commentId,
            'data' => $data
        ]);
        try {
            $result = [
                'status'  => 'error',
                'message' => trans('reviews.fail'),
            ];

            if (!empty($commentId)) {
                $data = $this->convertDataSave($data);
                $update = DB::table($this->_commentModel->getTableComment($shopId))
                    ->where('id', $commentId)->update($data);

                if ($update) {
                    // clear cache
                    $comment = $this->detail($shopId, $commentId);
                    if ($comment) {
                        event(new UpdateCache($shopId, $comment->product_id));
                    }
                    $result = [
                        'status'  => 'success',
                        'message' => trans('reviews.updateSuccess'),
                    ];
                }
            }

            return $result;
        } catch (Exception $ex) {

            $eventId = $this->sentry->captureException($ex);
            return [
                'stauts' => 'error',
                'message' => "{$ex->getMessage()}. EventId: {$eventId}"
            ];
        }
    }

    /**
     * @param $shopId
     * @param $type
     */
    public function updateImportReviewFromAliOrder($shopId , $type){

        $this->sentry->user_context([
            'shop_id' => $shopId,
            'type' => $type
        ]);
        try {
            $productRepo      =  new ProductsRepository();

            $tableName        = $this->_commentModel->getTableComment($shopId);

            $products         =  DB::connection( $this->_productModel->getConnectionName() )->table( $productRepo->getTableProduct( $shopId ) );

            $databaseProduct  =  DB::connection( $this->_productModel->getConnectionName() )->getDatabaseName();

            $tableComment     =  DB::table($tableName);

            $listProduct      =  $products->orderBy('created_at', 'desc')->get(['id'])->pluck('id')->toArray();

            $tableProducts    =  $products->from ;

            $listProduct      =  $tableComment->distinct()->join("".$databaseProduct.'.'.$tableProducts." as table_products", 'table_products.id', '=' , $tableComment->from.'.product_id')
                                ->whereIn('source', ['aliexpress','aliorder'])
                                ->whereIn('product_id', $listProduct)
                                ->orderBy('table_products.created_at' , 'desc')
                                ->limit(10)->get(['product_id'])
                                ->pluck('product_id')->toArray();

            if($listProduct){

                DB::table($tableName)->whereIn('product_id',   $listProduct)->whereIn('source', ['aliexpress','aliorder'])->update(['status'=>0]);

                array_map(function ($item) use ($shopId, $tableName,$type){

                    DB::table($tableName)->where('product_id', $item)->whereIn('source', ['aliexpress','aliorder'])->orderBy('created_at','desc')->limit(5)->update(['status'=>1]);

                },$listProduct);


                DB::table($tableName)->whereNotIn('product_id',   $listProduct)->whereIn('source', ['aliexpress','aliorder'])->update(['status'=>0]);

            }
        }catch (\Exception $exception){

            $eventId = $this->sentry->captureException($exception);

            return [
                'stauts' => 'error',
                'message' => "{$exception->getMessage()}. EventId: {$eventId}"
            ];
        }
    }
}