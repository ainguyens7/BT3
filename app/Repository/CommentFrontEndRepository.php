<?php

namespace App\Repository;


use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Models\CommentsModel;
use Faker\Factory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\UpdateCache;

/**
 * Class CommentFrontEndRepository
 * @package App\Repository
 */
class CommentFrontEndRepository
{
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	protected $_commentModel;
	protected $_commentInfoRepo;
	protected $_productRepo;

	private $cacheFrontend;


	/**
	 * CommentsRepository constructor.
	 */
	public function __construct()
	{
		$this->_commentModel = new CommentsModel();
		$this->_commentInfoRepo = RepositoryFactory::commentInfoRepository();
		$this->_productRepo = RepositoryFactory::productsRepository();
		$this->cacheFrontend = app('CacheFrontend');
	}

	/**
	 * Get Review In Product in front-end
	 *
	 * @param $product_id
	 * @param $shop_id
	 * @param null $currentPage
	 *
	 * @return bool
	 */

	public function getReviewProductFrontEnd($product_id, $shop_id, $currentPage = null,$ip = '',$products_not_in = array(),$sort_type, $star) {


        if(empty($product_id)){
            $hashCache = Helpers::generate_key_cache_hash($shop_id, $product_id, config('cache_frontend.key_hash_cache_review_page_frontend'));
        }else{
            $hashCache = Helpers::generate_key_cache_hash($shop_id, $product_id, config('cache_frontend.key_hash_cache_frontend'));
        }

		$fieldCachePagination = Helpers::generate_hash_field(config('cache_frontend.key_field_hash_pagination'), $shop_id, $currentPage,$star, $sort_type);
		// check cache exist and return
		if ($this->cacheFrontend->exist($hashCache, $fieldCachePagination)) {
			$cacheValue = json_decode($this->cacheFrontend->get($hashCache, $fieldCachePagination));
			return new LengthAwarePaginator($cacheValue->data, $cacheValue->total, $cacheValue->per_page, $cacheValue->current_page);
		}
		//Set currentPage để dùng ngoài frontend

		if (isset($currentPage)) {
			Paginator::currentPageResolver(function () use ($currentPage) {
				return $currentPage;
			});
		}

		$shopMetaRepo = new ShopMetaRepository();

		$shop_meta      = $shopMetaRepo->detail( $shop_id );
		if ( empty( $shop_meta ) ) {
			$settings = config( 'settings' );
		} else {
			$settings = $shop_meta->toArray();
			if ( $settings['setting'] ) {
				$settings['setting'] = (array) json_decode( $settings['setting'] );
			}
		}
		$setting = $settings['setting'];

		$paginate_number = isset($setting['max_number_per_page']) ? $setting['max_number_per_page'] :  config('common.paginate_front_end');


		$params['product_id'] = $product_id;
		$params['perPage'] = $paginate_number;
		$params['star'] = $star;
		$params['currentPage']  = !empty($currentPage) ? $currentPage : 1;
		// get list review frontend
		$data_reviews = $this->allReviews($shop_id , $params, $setting ,$sort_type);


		if(empty($data_reviews))
			return false;

		if(!empty($data_reviews) && (is_array($data_reviews) or is_object($data_reviews))){
			foreach ($data_reviews as $k => $comment) {
				$data_reviews[$k]->user_order_info = json_decode($comment->user_order_info);
				$data_reviews[$k]->img = json_decode($comment->img);

				$checkIpLike = $this->_commentInfoRepo->all($shop_id, [
					'comment_id' => $comment->id,
					'ip_like' => $ip,
					'perPage' => 1,
				]);
				$data_reviews[$k]->likeClass = !empty($checkIpLike->total()) ? 'active' : '';

				$checkIpUnlike = $this->_commentInfoRepo->all($shop_id, [
					'comment_id' => $comment->id,
					'ip_unlike' => $ip,
					'perPage' => 1,
				]);
				$data_reviews[$k]->UnlikeClass = !empty($checkIpUnlike->total()) ? 'active' : '';

				if(empty($product_id)){
					$comment->product_info  = array();
					$product_info = $this->_productRepo->detail($shop_id,$comment->product_id);
					if(!empty($product_info)){
						$product_info->product_link = '/products/'.$product_info->handle;
						$comment->product_info = $product_info;
					}
				}
			}
		}
		// add to cache
		$this->cacheFrontend->store($hashCache, $fieldCachePagination, $data_reviews->toJson());
		return $data_reviews;
	}



	/**
	 * Get average ratting
	 *
	 * @param $productId
	 * @param $shopId
	 *
	 * @return float
	 */
	public function getAvgStar( $productId, $shopId ) {
		// generate key hash and field
		$hashCache = Helpers::generate_key_cache_hash($shopId, $productId, config('cache_frontend.key_hash_cache_frontend'));
		$fieldCacheAvgStar = Helpers::generate_hash_field(config('cache_frontend.key_field_hash_avg_star'), $productId);
		// check exist in cache
		if ($this->cacheFrontend->exist($hashCache, $fieldCacheAvgStar)) {
			return $this->cacheFrontend->get($hashCache, $fieldCacheAvgStar);
		}
		$table = $this->_commentModel->getTableComment( $shopId ) ;
		$avg = DB::table($table)
		         ->where( 'product_id', '=', $productId )
		         ->where( 'status', '=',config('common.status.publish') );

		$result = 0;
		if ($avg) {
			$result = round( $avg->avg( 'star' ),1 );
		}
		// add cache
		$this->cacheFrontend->store($hashCache, $fieldCacheAvgStar, $result);
		return $result;
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
	public function getTotalStar( $productId, $shopId, $star = null, $status = [] ) {
		// generate key hash and field
		$hashCache = Helpers::generate_key_cache_hash($shopId, $productId, config('cache_frontend.key_hash_cache_frontend'));
		$fieldCacheTotalStar = Helpers::generate_hash_field(config('cache_frontend.key_field_hash_total_star'), $shopId, $star, implode('_', $status));
		// check exist in cache
		if ($this->cacheFrontend->exist($hashCache, $fieldCacheTotalStar)) {
			return $this->cacheFrontend->get($hashCache, $fieldCacheTotalStar);
		}
		$query = DB::table( $this->_commentModel->getTableComment( $shopId ) )
		           ->where( 'product_id', '=', $productId );
		if ( ! empty( $status ) ) {
			$query->whereIn( 'status', $status );
		}
		if ( isset( $star ) ) {
			$query->where( 'star', '=', $star );
		}

		$total = $query->count( 'star' );

		// store cache
		$this->cacheFrontend->store($hashCache, $fieldCacheTotalStar, $total);
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
		// generate key hash and field
		$hashCache = Helpers::generate_key_cache_hash($shop_id, $product_id, config('cache_frontend.key_hash_cache_frontend'));
		$fieldCacheTotalStatus = Helpers::generate_hash_field(config('cache_frontend.key_field_hash_total_star'), $productId, $status);
		// check exist in cache
		if ($this->cacheFrontend->exist($hashCache, $fieldCacheTotalStatus)) {
			return $this->cacheFrontend->get($hashCache, $fieldCacheTotalStatus);
		}

		$query = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'product_id', '=', $productId );
		if ( isset( $status ) ) {
			$query->where( 'status', '=', $status );
		}

		$query->where( 'status', '!=', config( 'custom.status.trash' ) );

		$total = $query->count( 'status' );
		// store cache
		$this->cacheFrontend->store($hashCache, $fieldCacheTotalStatus, $total);
		return $total;
	}


	/**
	 * Lưu lại data từ form review ngoài front end
	 * @param $res
	 * @param null $id
	 * @return bool
	 */
	public function saveForm($res, $id = null)
	{

		$faker = Factory::create();
		$commentModel = new CommentsModel();
		$table = $commentModel->getTableComment($res['shop_id']);
		$data = [
			'product_id' => !empty($res['product_id']) ? $res['product_id'] : 0,
			'author' => !empty($res['author']) ? $res['author'] : '',
			'content' => !empty($res['content'])?$res['content'] : '',
			'country' => !empty($res['country_code']) ? $res['country_code'] : '',
			'star' => !empty($res['star']) ? $res['star'] : 5,
			'avatar' => Helpers::getAvatarAbstract(),
			'img' => !empty($res['img']) ? $res['img'] : '',
			'email' => ! empty($res['email']) ? $res['email'] : $faker->email,
			'status' => isset($res['status']) ? $res['status'] : config('common.status.unpublish'),
			'source' => 'web',
			'verified' => '0',
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s")
		];

		$insert = DB::table($table)->insert($data);

		if ( ! $insert)
			return false;
		else
		{
			event(new UpdateCache($res['shop_id'],$res['product_id']));
			$productRepo = RepositoryFactory::productsRepository();
			$productRepo->update($res['shop_id'],$res['product_id'],['is_reviews' => 1,'updated_at' => date('Y-m-d H:i:s',time())]);
		}

		return true;
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

	/**
	 * Update comment
	 *
	 * @param $shopId
	 * @param $commentId
	 * @param $data
	 *
	 * @return array
	 */
	public function update( $shopId, $commentId, $data ): array {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'reviews.fail' ),
		);
		if ( ! empty( $commentId ) ) {
			$data = $this->convertDataSave( $data );

			$sql = DB::table( $this->_commentModel->getTableComment( $shopId ) )->where( 'id', $commentId );
			$comment = $sql->first();
			if (!empty($comment)) {
				event(new UpdateCache($shopId, $comment->product_id));
			}
			$update = $sql->update( $data );
			if ( $update ) {
				$result = array(
					'status'  => 'success',
					'message' => Lang::get( 'reviews.updateSuccess' ),
				);
			}
		}

		return $result;
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

		return $data;
	}

	/**
	 * Get Fillable Frontend
	 *
	 * @return array
	 */
	public function fillableFrontend() {
		$fillable = $this->_commentModel->fillableFrontend();

		return $fillable;
	}

	/**
	 * Get prefix
	 *
	 * @return mixed|string
	 */
	public function getPrefix(){

		return $this->_commentModel->prefix;
	}

    /**
     * @param string $shopId
     * @param array $params
     * @param $setting
     * @return array|LengthAwarePaginator
     */
    public function allReviews($shopId = '',  $params , $setting, $sort_type)
    {
        try {


            $listReviewImageContentNotNull = [];
            $listReviewContentNull = [];
            $listReviewImageNull = [];
            $listReviewImageContentNull = [];
            $listReviewByLike = [];
            $listReviewByDate = [];

            $sortFrontEnd =[];

            // get list review with PIN
            if (empty($params['product_id'])) {
                $result =   $this->getListReviewPage($shopId ,$params) ;
                return $result ;
            } else {

                $listReviewPin = $this->getReviewsCondition($shopId, $params, $setting, 'pin', null);

                if (!empty($sort_type) && $sort_type !== 'all') {
                    $listReviewContentNullSortType = [];
                    $listReviewContentNotNullSortType = [];
                    $listReviewImageNullSortType = [];
                    $listReviewImageNotNullSortType = [];
                    $sortFrontEndAll = [];
                    if ($sort_type !== 'stars' && $sort_type !== 'date') {

                        if ($sort_type == 'content') {
                            $listReviewContentNullSortType = $this->getReviewsCondition($shopId, $params, $setting, 'content-null', $sort_type);
                            $listReviewContentNotNullSortType = $this->getReviewsCondition($shopId, $params, $setting, 'content-not-null', $sort_type);
                        }

                        if ($sort_type === 'pictures') {
                            $listReviewImageNullSortType = $this->getReviewsCondition($shopId, $params, $setting, 'image-null', $sort_type);
                            $listReviewImageNotNullSortType = $this->getReviewsCondition($shopId, $params, $setting, 'image-not-null', $sort_type);
                        }

                    } else {
                        $sortFrontEndAll = $this->getReviewsCondition($shopId, $params, $setting, null, $sort_type);
                    }
                    $sortFrontEnd = array_merge($sortFrontEndAll, $listReviewContentNotNullSortType, $listReviewContentNullSortType, $listReviewImageNotNullSortType, $listReviewImageNullSortType);

                } else {

                    if (!empty($setting)) {
                        // sort by social
                        if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_social') {

                            // get list review has image and content
                            $listReviewImageContentNotNull = $this->getReviewsCondition($shopId, $params, $setting, 'not-null', null);
                            // get list review content null
                            $listReviewContentNull = $this->getReviewsCondition($shopId, $params, $setting, 'content-null', null);
                            // get list review image null
                            if (in_array($shopId, config('case_review_page_frontend.shop_id')) && empty($params['product_id'])) {

                                $listReviewImageNull = $this->caseReviewPageFrontend($shopId, $params, $setting, null);

                            } else {

                                $listReviewImageNull = $this->getReviewsCondition($shopId, $params, $setting, 'img_null', null);
                            }

                            // get list review image and content is null
                            $listReviewImageContentNull = $this->getReviewsCondition($shopId, $params, $setting, 'null', null);
                        }
                        // sort by like
                        if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_like') {
                            // get list sort date
                            $listReviewByLike = $this->getReviewsCondition($shopId, $params, $setting, 'by_like', null);
                        }
                        // sort by date
                        if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_date') {
                            // get list sort date
                            $listReviewByDate = $this->getReviewsCondition($shopId, $params, $setting, 'by_date', null);
                        }

                    }
                }


                // Merge all list condition reviews
                // dd($listReviewImageContentNotNull);
                $items = array_merge($listReviewPin, $listReviewImageContentNotNull, $listReviewContentNull, $listReviewImageNull, $listReviewImageContentNull, $listReviewByLike, $listReviewByDate, $sortFrontEnd);

                $perPage = !empty($params['perPage']) ? $params['perPage'] : config('common.paginate_front_end');

                $currentPage = !empty($params['currentPage']) ? $params['currentPage'] : 1;

                $slice = array_slice($items, (int)$perPage * ((int)$currentPage - 1), (int)$perPage);

                $result = (new LengthAwarePaginator($slice, count($items), (int)$perPage))->setPath(url()->current());

                return $result;
            }
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
    private function getReviewsCondition($shopId = '',  $params = [] , $setting, $option = null ,$sort_type)
    {
        try {
            $table   = $this->_commentModel->getTableComment($shopId);
            if (empty($params['product_id'])) {
                $review = DB::table($table)->where([
                    ['status', config('common.status.publish')]
                ])->whereNotIn('source',['default']);
            } else {
                $review = DB::table($table)->where([
                    ['product_id', $params['product_id']],
                    ['status', config('common.status.publish')]
                ]);
            }

            if (!empty($products_not_in) && is_array($products_not_in)) {
                $review->whereNotIn('product_id',$products_not_in);
            }
	        if (!empty($params['star']) && $params['star'] !='all') {
		        $review->where('star',$params['star']);
	        }
            if ($option === 'pin') {
                $review->where('pin', 1)->orderBy('updated_at', 'asc');
            }
            if(!empty($sort_type) && $sort_type != 'all'){

                if ($sort_type == 'stars') {

                    $review->where(function ($condition){

                        $condition->where('pin', 0)->orWhereNull('pin');

                    })->orderBy('star', 'desc');
                }

                if ($sort_type == 'date') {

                    $review->where(function ($condition){

                        $condition->where('pin', 0)->orWhereNull('pin');

                    })->orderBy('created_at', 'desc');
                }

                if ($sort_type == 'content') {

                    if($option == 'content-not-null'){

                        $review->where(function ($condition){

                            $condition->whereNotNull('content')->where('content', '!=', '');

                            $condition->where('pin', 0)->orWhereNull('pin')->orderBy('created_at', 'desc');

                        });
                    }

                    if($option == 'content-null'){

                        $review->where(function ($condition){

                            $condition->whereNull('content')->orWhere('content', '');
                            $condition->where('pin', 0)->orWhereNull('pin')->orderBy('created_at', 'desc');
                        });
                    }
                }

                if ($sort_type == 'pictures') {
                    if($option == 'image-null'){
                        $review->where(function ($condition){
                            $condition->whereNull('img')->orWhere('img', '');
                            $condition->where('pin', 0)->orWhereNull('pin')->orderBy('created_at', 'desc');

                        });
                    }


                    if($option == 'image-not-null'){

                        $review->where(function ($condition){
                            $condition->whereNotNull('img')->where('img', '!=', '');
                            $condition->where('pin', 0)->orWhereNull('pin')->orderBy('created_at', 'desc');
                        });
                    }


                }

            }else{

                if (!empty($setting)) {

                    if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_social') {

                        if ($option === 'not-null') {

                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->where(function ($condition){
                                $condition->where('img', '!=', '')->whereNotNull('img');
                            })->whereNotNull('content')->where('content', '!=', '')->orderBy('created_at', 'desc');
                        }

                        if ($option === 'content-null') {
                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->where(function ($condition){
                                $condition->whereNull('content')->orWhere('content', '');
                            })->whereNotNull('img')->where('img', '!=', '')->orderBy('created_at', 'desc');
                        }
                        if ($option === 'img_null') {

                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->where(function ($condition){
                                $condition->whereNull('img')->orWhere('img','');
                            })->where('content','<>', '')->whereNotNull('content')->orderBy('created_at', 'desc');
                        }
                        if ($option === 'null') {
                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->where(function ($condition){
                                $condition->whereNull('img')->orWhere('img', ' ');
                            })->where(function ($condition){
                                $condition->whereNull('content')->orwhere('content', ' ');
                            })->orderBy('created_at', 'desc');
                        }
                    }

                    if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_like') {
                        if ($option === 'by_like') {
                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->orderBy('like', 'desc')->orderBy('unlike', 'desc')->orderBy('created_at', 'desc');
                        }
                    }

                    if (isset($setting['sort_reviews']) && $setting['sort_reviews'] === 'sort_by_date') {
                        if ($option === 'by_date') {
                            $review->where(function ($condition){
                                $condition->where('pin', 0)->orWhereNull('pin');
                            })->orderBy('created_at', 'desc');
                        }
                    }
                }
            }

            $result = $review->orderBy('created_at', 'desc')->get()->toArray();

            return $result;
        } catch (Exception $ex) {
            $this->sentry->captureException($ex);
            return [];
        }
    }

    public function caseReviewPageFrontend($shopId,  $params , $setting = [], $option = null){

        try {
            $table   = $this->_commentModel->getTableComment($shopId);

            $review = DB::table($table)->where([
                ['status', config('common.status.publish')]
            ])->whereNotIn('source',['default']);

            if (!empty($products_not_in) && is_array($products_not_in)) {

                $review->whereNotIn('product_id',$products_not_in);

            }
            if (!empty($params['star']) && $params['star'] !='all') {

                $review->where('star',$params['star']);

            }
            if ($option === 'pin') {

                $review->where('pin', 1)->orderBy('updated_at', 'asc');

            }
            $review->where(function ($condition){

                $condition->where('pin', 0)->orWhereNull('pin');

            })->where(function ($condition){

                $condition->whereNull('img')->orWhere('img','');

            })->where('content','<>', '')->whereNotNull('content');

            $result = $review->orderBy('created_at', 'desc')->get()->toArray();

            return $result;

        } catch (Exception $ex) {

            $this->sentry->captureException($ex);
            return [];
        }
    }

    /**
     * @param $shopId
     * @param array $params
     * @return mixed
     */
    public function getListReviewPage($shopId , $params = []){
        try{
            $perPage = isset( $params['perPage'] ) ? $params['perPage'] : config( 'common.pagination' );
            $table   = $this->_commentModel->getTableComment($shopId);
            $query   = DB::table($table);
            $result  = $query->where([
                ['status', config('common.status.publish')]
            ])->whereNotIn('source',['default'])->orderBy('created_at', 'desc')->paginate($perPage) ;
            return $result ;
        } catch (\Exception $exception){
            $this->sentry->captureException($exception);
            return [];
        }
    }
}
