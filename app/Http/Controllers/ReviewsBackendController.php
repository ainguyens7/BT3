<?php

namespace App\Http\Controllers;

use App\Events\AddGoogleRatingEvent;
use App\Events\UpdateCache;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Repository\ProductsRepository;
use App\ShopifyApi\ProductsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use Intervention\Image\Facades\Image;
use App\Events\ProductActionReview;

class ReviewsBackendController extends Controller {

	private $_productApi;
	private $_commentRepo;
	private $_shopMetaRepo;
	private $_shopRepo;
	private $_productRepo;
	private $_LogRepo;

	private $shopifyDomain;

	private $accessToken;


	const ACTION_PUBLISH = 'publish';
	const ACTION_UNPUBLISH = 'unpublish';
	const ACTION_DELETE = 'delete';
	const PLAN_FREE = 'free';
	const PLAN_PREMIUM = 'premium';
	const PLAN_UNLIMITED = 'unlimited';

	function __construct( RepositoryFactory $factory ) {
		$this->_productApi   = new ProductsApi();
		$this->_commentRepo  = $factory::commentBackEndRepository();
		$this->_shopMetaRepo = $factory::shopsMetaReposity();
		$this->_productRepo  = $factory::productsRepository();
		$this->_shopRepo     = $factory::shopsReposity();
		$this->_LogRepo     = $factory::logRepository();
	}

	/**
	 * Manage review of product
	 *
	 * @param $productId
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function manageProductReview($productId = '', Request $request) 
	{
		$filters = $request->all();
		$shopId = session('shopId');

		$product = $this->_productRepo->detail($shopId, $productId);

		if (!$product) {
			return view( 'page_errors.product-not-found', [ 'message' => Lang::get( 'reviews.error_empty_product' ) ] );
		}

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
		if (!empty($shopPlanInfo['status'])) {
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		}

		$statistic = $this->getStatisticReview($productId);
		$filters['product_id'] = $productId;
		$settings = Helpers::getSettings($shopId);

		$pictureOption = [];
		$contentOption = [];

        if (array_key_exists('get_only_picture', $settings['setting'])) {
			$picSetting = $settings['setting']['get_only_picture'];
            $pictureOption = is_array($picSetting) ? ((in_array("1",$picSetting)) ? ["true", "false"] :  $picSetting ): ( $picSetting === "all"  ? ["true", "false"] : [$picSetting] );
			$pictureOption = Helpers::transformValueArrayToString($pictureOption);
        }
        
        if (array_key_exists('get_only_content', $settings['setting'])) {
            $contentSetting = $settings['setting']['get_only_content'];
            $contentOption = is_array($contentSetting) ? ((in_array("1",$contentSetting)) ? ["true", "false"] :  $contentSetting )  : [$contentSetting];
			$contentOption = Helpers::transformValueArrayToString($contentOption);
        }

        $settings['setting']['get_only_picture'] = $pictureOption;
        $settings['setting']['get_only_content'] = $contentOption;

        $countrySetting = (isset($settings['setting']['country_get_review']) &&  !empty($settings['setting']['country_get_review'])) ? $settings['setting']['country_get_review']  : [];

        $arraySetting = [] ;
        $i = 0 ;
        if (!empty($countrySetting)) {
            foreach ($countrySetting as $key=>$item){
                if(in_array($item ,config('code_country_old_alireview.old'))){
                    $arraySetting[] = config('code_country_old_alireview.new')[$i];
                    $i++;
                }else{
                    $arraySetting[] = $item;
                }
            }
            $settings['setting']['country_get_review'] = $arraySetting;
        }

		$shopMeta = json_encode( $settings['setting'] );
		$allCountry = json_encode( Helpers::getCountryCode(), true );

		$listReview = $this->_commentRepo->allReviews($shopId, $filters, $settings['setting']);
		if (!empty($_GET['page']) && $_GET['page'] != 1 && empty(count( $listReview))) {
			return redirect(route( 'reviews.product', ['productId' => $productId]));
		}
        $listAllCountry  =  Helpers::getCountryCode();
		// unset ở phân trang.
		unset($filters['product_id']);
		$reviewSources = config('common.review_sources');
		return view('reviewsBackend.manage_product_review', compact('listAllCountry','product', 'filters', 'listReview', 'statistic', 'shopMeta', 'allCountry', 'shopPlanInfo', 'reviewSources', 'countrySetting'));
	}

	/**
	 * Get statistic review for product
	 *
	 * @param $productId
	 *
	 * @return array
	 */
	public function getStatisticReview( $productId ) {
		$shopId    = session( 'shopId' );
		$statistic = array();

		$statistic['avg_star']     = $this->_commentRepo->getAvgStar( $productId, $shopId );
		$statistic['total_star']   = $this->_commentRepo->getTotalStar( $productId, $shopId );
		$statistic['total_star_5'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 5 );
		$statistic['total_star_4'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 4 );
		$statistic['total_star_3'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 3 );
		$statistic['total_star_2'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 2 );
		$statistic['total_star_1'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 1 );
		if ( $statistic['total_star'] > 0 ) {
			$statistic['percent_star_5'] = ( $statistic['total_star_5'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_4'] = ( $statistic['total_star_4'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_3'] = ( $statistic['total_star_3'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_2'] = ( $statistic['total_star_2'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_1'] = ( $statistic['total_star_1'] * 100 ) / $statistic['total_star'];
		}

		$statistic['total_reviews'] = $this->_commentRepo->getTotalStatus( $productId, $shopId );

		return $statistic;
	}

	/**
	 * Page approve review
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function reviewApprove() {
		$filter = array(
			'status' => 'unpublish',
			'source' => 'web',
		);

		$filter = array_merge( $filter, $_GET );

		$listReview = $this->_commentRepo->all( session( 'shopId' ), $filter );

		if ( ! empty( $_GET['page'] ) && $_GET['page'] != 1 && empty( count( $listReview ) ) ) {
			return redirect( route( 'reviews.review_approve' ) );
		}

		return view( 'reviewsBackend.review_approve', compact( 'listReview' ) );
	}

	/**
	 * Page approve review
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function reviewsHistory() {
		$filter = array(
			'status' => 'publish',
			'source' => 'web',
		);

		$filter = array_merge( $filter, $_GET );

		$listReview = $this->_commentRepo->all( session( 'shopId' ), $filter );

		if ( ! empty( $_GET['page'] ) && $_GET['page'] != 1 && empty( count( $listReview ) ) ) {
			return redirect( route( 'reviews.review_approve' ) );
		}

		return view( 'reviewsBackend.history_reviews', compact( 'listReview' ) );
	}

	/**
	 * Ajax approve review
	 *
	 * @param Request $request
	 */
	public function reviewApproveHandel( Request $request ) {
		if ( $request->ajax() ) {
			$data = $request->all();

			if ( ! empty( $data['comment_id'] ) ) {
				$commentId = $data['comment_id'];
				$type      = ! empty( $data['type'] ) ? $data['type'] : 'approve';

				if ( $type == 'approve' ) {
					$update = $this->_commentRepo->update( session( 'shopId' ), $commentId, array( 'status' => 1 ) );
					if ( $update['status'] == 'success' ) {
						$update['message'] = Lang::get( 'reviews.approve_success' );
					}
				} else {
					$update = $this->_commentRepo->update( session( 'shopId' ), $commentId, array( 'status' => 0 ) );
					if ( $update['status'] == 'success' ) {
						$update['message'] = Lang::get( 'reviews.disapprove_success' );
					}
				}

				echo json_encode( $update );
				exit();
			}
		}
	}


	/**
	 * Ajax get review info
	 *
	 * @param Request $request
	 */
	public function reviewInfo( Request $request ) {
		if ( $request->ajax() ) {
			$data = $request->all();
			if ( ! empty( $data['comment_id'] ) ) {
				$commentId = $data['comment_id'];
				$review    = $this->_commentRepo->detail( session( 'shopId' ), $commentId );

                $review->created_at  = date('d/m/Y',strtotime($review->created_at));
				if ( $review ) {
					$review->shop_id = session( 'shopId' );
				}
				echo json_encode( $review );
				exit();
			}
		}
	}

	/**
	 * Ajax pin review
	 *
	 * @param Request $request
	 */
	public function reviewPin( Request $request ) {
		if ( $request->ajax() ) {
			$data = $request->all();

			$shopPlanInfo = $this->_shopRepo->shopPlansInfo( session( 'shopId' ) );
			if ( $shopPlanInfo['status'] ) {
				if ( ! empty( $shopPlanInfo['planInfo']['pin'] ) ) {
					if ( ! empty( $data['comment_id'] ) ) {
						$commentId = $data['comment_id'];
						$type      = ! empty( $data['type'] ) ? $data['type'] : '';
						if ( $type == "pin" ) {
							$pin = $this->_commentRepo->update( session( 'shopId' ), $commentId, array( 'pin' => 1 ) );
						} else {
							$pin = $this->_commentRepo->update( session( 'shopId' ), $commentId, array( 'pin' => 0 ) );
						}

						echo json_encode( $pin );
						exit();
					}
				}
			}
		}
	}


	/**
	 * Ajax delete review
	 *
	 * @param Request $request
	 */
	public function reviewDelete( Request $request ) {
		if ( $request->ajax() ) {
			$data = $request->all();

			if ( ! empty( $data['comment_id'] ) ) {
				$commentId = $data['comment_id'];

				$delete = $this->_commentRepo->delete( session( 'shopId' ), $commentId );
				echo json_encode( $delete );
				exit();
			}
		}
	}


	/**
	 * Ajax update review
	 *
	 * @param Request $request
	 */
	public function reviewUpdate( Request $request ) {
		if ( $request->ajax() ) {
			$data = $request->all();

			if ( ! empty( $data['comment_id'] ) ) {
				$commentId = $data['comment_id'];
				unset( $data['comment_id'] );
				unset( $data['_token'] );
				if ( ! isset( $data['img'] ) ) {
					$data['img'] = null;
				}

				if(empty($data['created_at'])){
                    unset($data['created_at']);
                }else{
                    $lisDate = explode('/', $data['created_at']);
                    $data['created_at']   = date('Y-m-d H:i:s', strtotime($lisDate[0].'-'.$lisDate[1].'-'.$lisDate[2]));
                }
				$update = $this->_commentRepo->update( session( 'shopId' ), $commentId, $data );
				echo json_encode( $update );
				exit();
			}
		}
	}

	public function checkShowPopup3($shopId) {
		$newUser = $this->_logRepo->checkFreeUser($shopId);
		if ($newUser) {
			Redis::set($shopId.'_numberShowPopup3', 1);
			return true;
		}
		return false;
	}

	/**
	 * Api change status of review
	 *
	 * @param Request $request
	 */

	public function reviewChangStatus(Request $request)
	{	
		$result = [];
		$shopId = session('shopId');
		$shopFind = $this->_shopRepo->detail(['shop_id' => $shopId]);
		if (!$shopFind['status']) {
			return response()->json($shopFind, 500);
		}
		$shopInfo = $shopFind['shopInfo'];
		$source = $request->input('source', '');
		$action = $request->input('action', '');
		$commentId = $request->input('comment_id', '');
		$productId = $request->input('product_id', '');
		$appPlan = $shopInfo->app_plan;

		if ($action === 'publish') {
			switch ($appPlan) {
				case self::PLAN_FREE:
					return $this->handleChangeStatusPlanFree($shopId, $productId, $commentId, $source);
					break;
				case self::PLAN_PREMIUM:
					return $this->handleChageStatusPlanPremium($shopId, $productId, $commentId, $source);
					break;
				case self::PLAN_UNLIMITED:
					return $this->handleChangeStatusPlanUnlimited($shopId, $commentId);
					break;
				default: break;
			}
		}

		$resultUpdate = $this->_commentRepo->update($shopId, $commentId, ['status' => 0]);

		if ($resultUpdate['status'] === 'error') {
			return response()->json($resultUpdate, 500);
		}

		return response()->json($resultUpdate, 200);
	}

	private function handleChangeStatusPlanFree($shopId = '', $productId = '', $commentId = '', $source = 'aliexpress')
	{
		$showPopup3 = false;
		$numberShowPopup3 = Redis::get(session( 'shopId' ).'_numberShowPopup3');
		$plan = $source === 'oberlo' ? 'Unlimited' : 'Premium';
		$avaiableSource = ['aliexpress', 'web', 'aliorder'];
		$result = [];
        if (!in_array($source, $avaiableSource)) {
			$result['status'] = trans('api.status', ['status' => 'error']);
			$result['message'] = trans('api.required_paid_version', ['paid' => $plan]);
			
			return response()->json($result, 200);
		}

        if ($source === 'aliexpress' || $source === 'aliorder') {
            $source = ['aliexpress','aliorder' ];
			$should = $this->checkResourceCanPublish($shopId, $productId, $source);
			if (!$should['status']) {
                $result['error'] = trans('api.status', ['status' => 'error']);
				$result['message'] = trans('api.required_paid_version', ['paid' => $plan]);
				$result['showPopup3'] = $showPopup3;

				return response()->json($result, 200);
			}
		}

		return $this->changeStatusPlan($shopId, $commentId);
	}

	private function handleChageStatusPlanPremium($shopId = '', $productId = '', $commentId = '', $source = 'aliexpress')
	{
		$plan = 'Unlimited';
		$avaiableSource = ['aliexpress', 'web', 'default','aliorder'];
		if (!in_array($source, $avaiableSource)) {
			$result['status'] = trans('api.status', ['status' => 'error']);
			$result['message'] = trans('api.required_paid_version', ['paid' => $plan]);

			return response()->json($result, 200);
		}

		return $this->changeStatusPlan($shopId, $commentId);
	}

	private function checkResourceCanPublish($shopId = '', $productId = '', $source = '')
	{
//        $countProductImportedReview = $this->_productRepo->countReviewProduct($shopId,'import');
        $shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
        if (!empty($shopPlanInfo['status'])) {
            $shopPlanInfo = $shopPlanInfo['planInfo'];
            if(!empty($shopPlanInfo['total_product'])){
                $listProductsImpotedReviews = $this->_productRepo->getListProductsImportedReviews($shopId);
                $countProductImportedReview = count($listProductsImpotedReviews);
                if(($countProductImportedReview >= $shopPlanInfo['total_product']) && !in_array($productId,$listProductsImpotedReviews)) {
                    return [
                        'status' => false,
                        'remaining' => 0
                    ];
                }
            }
        }

		// aliexpress are 5, default are 20
		$avaiableSource = 5;
		$find = $this->_commentRepo->findReview(
			$shopId,
			$productId,
			['source' => $source, 'status' => 1]
		);
		if (!$find['status']) {
			$result['error'] = trans('api.status', ['status' => 'error']);
			$result['message'] = $find['message'];
			
			return response()->json($result, 500);
		}

		$result = $find['result']->get();
		$total = count($result);

		// premium version can only publish avaiable default reviews
		if ($total >= $avaiableSource) {
			return [
				'status' => false,
				'remaining' => 0
			];
		}
		return [
			'status' => true,
			'remaining' => $avaiableSource - $total
		];
	}
	private function handleChangeStatusPlanUnlimited($shopId = '', $commentId = '')
	{
		return $this->changeStatusPlan($shopId, $commentId);
	}

	private function changeStatusPlan($shopId = '', $commentId = '')
	{
	    $shopFind = $this->_shopRepo->detail(['shop_id'=>$shopId]);

        $shopInfo = $shopFind['shopInfo'];

        if ($shopInfo->app_plan === 'free') {

            $result = $this->_commentRepo->updateReviewPlanFree($shopId, $commentId, ['status' => 1]);

            if ($result['status'] === 'error') {
                $result['error'] = trans('api.status', ['status' => 'error']);
                $result['showPopup3'] = false;
                return response()->json($result, 200);
            }

        }else{
            $result = $this->_commentRepo->update($shopId, $commentId, ['status' => 1]);
        }

		if ($result['status'] === 'error') {
			return response()->json($result, 200);
		}

		return response()->json($result, 200);
	}

	/**
	 * Api multi action reviews
	 *
	 * @param Request $request
	 */
	public function multiReviewAction(Request $request)
	{
		$result = [];
        $urlParam   = [];
		$unSerializeReview      = $request->input('listReviewCheck', []);
		$action                 = $request->input('action', '');
		$type                   = $request->input('type', '');
		$typePage               = $request->input('typePage', '');
        $uncheckCurrentPage     = $request->input('uncheckCurrentPage', '') ;
        $uncheckCurrentPage     = ($uncheckCurrentPage) ? explode(',', trim($uncheckCurrentPage)) : [];
        $urlParam['star']       = $request->input('star', '') ;
        $urlParam['source']     = $request->input('source', '') ;
        $urlParam['keyword']    = $request->input('keyword', '') ;
        $urlParam['status']     = $request->input('status', '') ;
        $productID              = $request->input('productID', '') ;
        $serializeReview        = [];
		if (!empty($unSerializeReview)) {
			foreach($unSerializeReview as $iter) {
				$serializeReview[] = [
					'id' => array_key_exists('value', $iter) ? $iter['value'] : '',
					'source' => array_key_exists('source', $iter) ? $iter['source'] : '',
					'product_id' => array_key_exists('product_id', $iter) ? $iter['product_id'] : ''
				];
			}
		}

		$shopId = session('shopId');
        $haystack = $this->getNeedle($serializeReview);


		if ($type === 'approve') {

            if ($request->isAllReviews === 'true' || $request->isAllReviews === true) {

                $result = $this->_commentRepo->updateAllCommentPending($shopId, ['status' => 1], $uncheckCurrentPage);
                $statusCode = $result['status'] === 'error' ? 500 : 200;
                return response()->json($result, $statusCode);

            }

            $comment = array_map(function($value) {
                return $value['id'];
             }, $serializeReview);

			$result = $this->_commentRepo->updateComments($shopId, $comment, ['status' => 1], $urlParam);
            $statusCode = $result['status'] === 'error' ? 500 : 200;

			return response()->json($result, $statusCode);
		}

        if ($request->isAllReviews === 'false' || $request->isAllReviews === false){
            if(empty($haystack)) {
                $result['error'] = trans('api.status', ['status' => 'error']);
                $result['message'] = 'invalid parameters';
                return response()->json($result, 500);
            }
        }

		switch ($action) {
			case self::ACTION_PUBLISH:
                $shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
                if (!empty($shopPlanInfo['status'])) {
                    $shopPlanInfo = $shopPlanInfo['planInfo'];
                    if(!empty($shopPlanInfo['total_product'])){
                        $listProductsImportedReviews = $this->_productRepo->getListProductsImportedReviews($shopId);
                        $result['listProductsImportedReviews'] = $listProductsImportedReviews;
                        $countProductImportedReview = count($listProductsImportedReviews);
                        // nếu product id nằm trong danh sách product đã import review -- hoặc --- số lượng product đã import review < số lượng được import thì mới cho phép publish
                        if (($countProductImportedReview >= $shopPlanInfo['total_product']) && !in_array($productID, $listProductsImportedReviews)) {
                            $result['error'] = trans('api.status', ['status' => 'error']);
                            $result['message'] = trans('api.required_paid_version', ['paid' => $shopPlanInfo['next_plan']]);
                            return response()->json($result, 200);
                        }
                    }
                }
                if ($request->isAllReviews === 'true' || $request->isAllReviews === true) {
                    return $this->handlePublishMultiReview($shopId, $productID,$uncheckCurrentPage,$typePage  , $urlParam);

                }
				return $this->handlePublishMultiAction($shopId, $productID, $serializeReview);

				break;
			case self::ACTION_UNPUBLISH:
                if ($request->isAllReviews === 'true' || $request->isAllReviews === true) {

                    return $this->handleUnPublishMultiReview($shopId, $productID ,$uncheckCurrentPage ,$typePage  , $urlParam);

                }
				$comment = $haystack['comment'];

				return $this->handleUnpubMultiAction($shopId, $comment,$urlParam);

				break;
			case self::ACTION_DELETE:
			    if($request->isAllReviews === 'true' || $request->isAllReviews === true){

                    return $this->handleDeleteMultiReview($shopId, $productID, $uncheckCurrentPage ,$typePage  , $urlParam);
                }
                $comment = $haystack['comment'];
				return $this->handleDeleteMultiAction($shopId, $comment ,$urlParam);
				break;
			default: break;
		}
	}

	private function getNeedle($serializeReview)
	{
        $products = array_filter($serializeReview, function($value, $key) {
            return !empty($value['product_id']);
        }, ARRAY_FILTER_USE_BOTH);

        if (!isset($products[0])) {
            return [];
        }

        $productId = $products[0]['product_id'];
		$comment = array_map(function($value) {
			return $value['id'];
		}, $serializeReview);

		return [
			'product_id' => $productId,
			'comment' => $comment
		];
	}
	private function filterSource($data, $source = '')
	{
		return array_filter($data, function($comment, $key) use (&$source) {
			$value = array_key_exists('source', $comment) ? $comment['source'] : '';
			if (is_array($source)) {
				return in_array($value, $source);
			}
			return $value === $source;
		}, ARRAY_FILTER_USE_BOTH);
	}

	private function internalPipeUpdate($shopId, $productId, $source = 'aliexpress', $data)
	{
		$should = $this->checkResourceCanPublish($shopId, $productId, $source);

		if ($should['status']) {
			$remaining = $should['remaining'];
			$slice = array_slice($data, 0, $remaining );
			$comment = array_map(function($value) {
				return $value['id'];
			}, $slice);
			$result = $this->_commentRepo->updateComments($shopId, $comment, ['status' => 1]);
			return $result;
		}
	}
	private function handlePlanFreeMultiAction($shopId = '', $productId = '', $data)
	{
		// filter aliexpress source
		$aliSource = $this->filterSource($data, 'aliexpress');
		// find current aliexpress has publish
		if (!empty($aliSource)) {
			$this->internalPipeUpdate($shopId, $productId, 'aliexpress', $aliSource);
		}
		return response()->json([
			'status' => 'success',
			'message' => trans('api.update_review_success_paid_version', ['paid' => 'Free'])
		], 200);
	}

	private function handlePlanPremiumMultiAction($shopId = '', $productId = '', $data = [])
	{
		$source = $this->filterSource($data, ['default', 'aliexpress']);
		
		// update all source
		if (!empty($source)) {
			$comment = array_map(function($value) {
				return $value['id'];
			}, $source);
			$result = $this->_commentRepo->updateComments($shopId, $comment, ['status' => 1], null);
		}
		return response()->json([
			'status' => 'success',
			'message' => trans('api.update_review_success_paid_version', ['paid' => 'Premium'])
		], 200);
	}

	private function handlePlanUnlimitedMultiAction($shopId = '', $data)
	{
		$comment = array_map(function($value) {
			return $value['id'];
		}, $data);
		$result = $this->_commentRepo->updateComments($shopId, $comment, ['status' => 1],null);
		$statusCode = $result['status'] === 'error' ? 500 : 200;
		return response()->json($result, $statusCode);
	}

	private function handlePublishMultiAction($shopId = '', $productId = '', $serializeReview = [])
	{
		$shopRaw = $this->_shopRepo->detail(['shop_id' => $shopId]);

		if (!$shopRaw['status']) {
			$result['error'] = trans('api.status', ['status' => 'error']);
			$result['message'] = $shopRaw['message'];
			return response()->json($result, 500);
		}
		$shopInfo = $shopRaw['shopInfo'];
		$appPlan = $shopInfo->app_plan;

		// publish customer reviews

		$webSource = $this->filterSource($serializeReview, 'web');
		$comment = array_map(function($value) {
			return $value['id'];
        }, $serializeReview);
		if (!empty($comment)) {
			$this->_commentRepo->updateComments($shopId, $comment, ['status' =>  1],null );
		}
		switch ($appPlan) {
			case self::PLAN_FREE:
				return $this->handlePlanFreeMultiAction($shopId, $productId, $serializeReview);
				break;
			case self::PLAN_PREMIUM:
				return $this->handlePlanPremiumMultiAction($shopId, $productId, $serializeReview);
				break;
			case self::PLAN_UNLIMITED:
				return $this->handlePlanUnlimitedMultiAction($shopId, $serializeReview);
				break;
			default: break;
		}
	}

	private function handleUnpubMultiAction($shopId = '', $comment = [] ,$urlParam = [])
	{
		$result = $this->_commentRepo->updateComments($shopId, $comment, ['status' => 0], $urlParam);
		$statusCode = $result['status'] === 'success' ? 200 : 500;
		return response()->json($result, $statusCode);
	}
	private function handleDeleteMultiAction($shopId = '', $comment = [], $urlParam = [])
	{
		$result = $this->_commentRepo->deleteComments($shopId, $comment ,$urlParam);
		$statusCode = $result['status'] === 'success' ? 200 : 500;
		return response()->json($result, $statusCode);
	}




	/**
	 * Handle request action on top managemnt review
	 *
	 * @param Request $request
	 */
	public function allReviewAction(Request $request)
	{
		$result = [
			'status' => trans('api.status', ['status' => 'success']),
			'message' => trans('api.no_action')
		];

		$shopId = session('shopId');
		$shopRaw = $this->_shopRepo->detail(['shop_id' => $shopId]);

		if (!$shopRaw['status']) {
			$result['error'] = trans('api.status', ['status' => 'error']);
			$result['message'] = $shopRaw['message'];
			return response()->json($result, 500);
		}

		$shopInfo = $shopRaw['shopInfo'];
		$this->shopifyDomain = $shopInfo->shop_name;
		$this->accessToken = $shopInfo->access_token;
		$productId = $request->input('product_id', '');
		$productInfo = $this->_productRepo->detail($shopId, $productId);

		if (empty($productInfo)) {
			$result['status'] = trans('api.status', ['status' => 'success']);
			$result['message'] = trans('api.no_action_for_product');
			return response()->json($result, 200);
		}

		$action = $request->input('action', '');

		switch ($action) {
			case self::ACTION_PUBLISH:
				return $this->handlePublishAction($shopId, $productId);
				break;
			case self::ACTION_UNPUBLISH:
				return $this->handleUnpublishAction($shopId, $productId);
				break;
			case self::ACTION_DELETE:
				return $this->handleDeleteAction($shopId, $productId);
				break;
			default: break;
		}
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteReviewsInShopAction(Request $request)
	{
		$shopId = session('shopId');
		$shopRaw = $this->_shopRepo->detail(['shop_id' => $shopId]);

		if (!$shopRaw['status']) {
			$result['error'] = trans('api.status', ['status' => 'error']);
			$result['message'] = $shopRaw['message'];
			return response()->json($result, 500);
		}

		$shopInfo = $shopRaw['shopInfo'];
		$this->shopifyDomain = $shopInfo->shop_name;
		$this->accessToken = $shopInfo->access_token;
		$action = $request->input('action', '');

		$result = $this->_commentRepo->deleteCommentBySourceV2($shopId,$action);

		return response()->json($result, 200);
	}

	protected function handleUnpublishAction($shopId = '', $productId = '')
	{
		$result = $this->_commentRepo->updateAll(
			$shopId,
			$productId,
			['status' => '0']
		);
		event(new ProductActionReview($shopId, $productId, $this->shopifyDomain, $this->accessToken));
        event(new UpdateCache($shopId,$productId));
        return response()->json($result, 200);
	}

	protected function handleDeleteAction($shopId = '', $productId = '')
	{
		$result = $this->_commentRepo->deleteAll($shopId, $productId);
		event(new ProductActionReview($shopId, $productId, $this->shopifyDomain, $this->accessToken));
        event(new UpdateCache($shopId,$productId));
		return response()->json($result, 200);
	}


	protected function handlePublishAction($shopId = '', $productId = '')
	{
		$result = [];
		$shopRaw = $this->_shopRepo->detail(['shop_id' => $shopId]);
        $shopInfo = $shopRaw['shopInfo'];

		if (!$shopRaw['status']) {
			$result['status'] = trans('api.status', ['status' => 'error']);
			$result['message'] = $shopInfo['message'];
			return response()->json($result, 500);
		}

		$appPlan = $shopInfo->app_plan;
        $shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
        if (!empty($shopPlanInfo['status'])) {
            $shopPlanInfo = $shopPlanInfo['planInfo'];
            if(!empty($shopPlanInfo['total_product'])){
                $listProductsImportedReviews = $this->_productRepo->getListProductsImportedReviews($shopId);
                $result['listProductsImportedReviews'] = $listProductsImportedReviews;
                $countProductImportedReview = count($listProductsImportedReviews);
                // nếu product id nằm trong danh sách product đã import review -- hoặc --- số lượng product đã import review < số lượng được import thì mới cho phép publish
                if (($countProductImportedReview >= $shopPlanInfo['total_product']) && !in_array($productId, $listProductsImportedReviews)) {
                    $result['error'] = trans('api.status', ['status' => 'error']);
                    $result['message'] = trans('api.required_paid_version', ['paid' => $shopPlanInfo['next_plan']]);
                    return response()->json($result, 200);
                }
            }
        }

		if ($appPlan === 'free') {
            $this->updateReviewRemaining($shopId, $productId, 'free', 'aliorder');
            // find aliexpress review can public and update remaining
            $this->updateReviewRemaining($shopId, $productId, 'free', 'aliexpress');
			// find and update customer review on web
			$this->updateReview($shopId, $productId, 'web');

			$result['status'] = trans('api.status', ['status' => 'success']);
			$result['message'] = trans('api.update_review_success_paid_version', ['paid' => ucfirst($appPlan)]);
			event(new ProductActionReview($shopId, $productId, $this->shopifyDomain, $this->accessToken));
			event(new UpdateCache($shopId,$productId));
			return response()->json($result, 200);
		}

		if ($appPlan === 'premium') {
            $this->updateReview($shopId, $productId, 'aliorder');
			// publish aliexpress reviews
			$this->updateReview($shopId, $productId, 'aliexpress');
			// premium can publish only 20 default reviews
			$this->updateReview($shopId, $productId, 'default');
			// find and update customer review on web
			$this->updateReview($shopId, $productId, 'web');

			$result['status'] = trans('api.status', ['status' => 'success']);
			$result['message'] = trans('api.update_review_success_paid_version', ['paid' => ucfirst('pro')]);
			event(new ProductActionReview($shopId, $productId, $this->shopifyDomain, $this->accessToken));
            event(new UpdateCache($shopId,$productId));
            return response()->json($result, 200);
		}

		$result = $this->_commentRepo->updateAll(
			$shopId,
			$productId,
			['status' => '1']
		);
		event(new ProductActionReview($shopId, $productId, $this->shopifyDomain, $this->accessToken));
        event(new UpdateCache($shopId,$productId));
        return response()->json($result, 200);
	}

	private function updateReviewRemaining($shopId, $productId, $plan = 'free', $source = 'aliexpress')
	{
		$aviableReview = 5;
		$find = $this->_commentRepo->findReview($shopId, $productId, ['source' => $source, 'status' => 1]);

		if ($find['status']) {
			$result = $find['result']->get();
			$total = count($result);

			if ($total < $aviableReview) {
				$remaining = (int) $aviableReview - $total;
				$re = $this->_commentRepo->findReview($shopId, $productId, ['source' => $source, 'status' => 0, 'limit' => $remaining]);

				if ($re['status']) {
					$re['result']->update(['status' => 1]);
				}
			}
		}
	}

	private function updateReview($shopId = '', $productId = '', $source = 'aliexpress')
	{
		$result = $this->_commentRepo->findReview($shopId, $productId, ['source' => $source, 'status' => 0]);
		if ($result['status']) {
			$result = $result['result']->update(['status' => 1]);
			return $result;
		}
	}


	/**
	 * Ajax update file
	 *
	 * @param Request $request
	 */
	public function uploadFile( Request $request ) {
		$result  = array( 'status' => 'error', 'message' => Lang::get( 'reviews.fail' ) );
		$shop_id = session( 'shopId' );
		if ( $request->ajax() && ! empty( $shop_id ) && $_FILES ) {
			$file       = array_shift( $_FILES );

			$image_name = time() . md5( $file['name'] ) . '.jpg';
			list( $width, $height ) = getimagesize( $file['tmp_name'] );
			$path = storage_path( 'app/public/uploads/' . $shop_id . '/_thumb/' );
			File::makeDirectory( $path, $mode = 0777, true, true );

			// resize image to thumb image in storage
			if ( $width > $height ) {
				$resize = Image::make( $file['tmp_name'] )
				               ->resize( null, 300, function ( $constraint ) {
					               $constraint->aspectRatio();
				               } )
				               ->crop( 300, 300 )->save( $path . $image_name );
			} else {
				$resize = Image::make( $file['tmp_name'] )
				               ->resize( 300, null, function ( $constraint ) {
					               $constraint->aspectRatio();
				               } )
				               ->crop( 300, 300 )->save( $path . $image_name );
			}

			if ( $resize ) {
				/**
				 * ftp upload image to server imgs.fireapps.vn
				 */
				// set up basic connection
				$conn_id = ftp_connect( config( 'common.ftp_host' ) );
				// login with username and password
				$login_result = ftp_login( $conn_id, config( 'common.ftp_user' ), config( 'common.ftp_pass' ) );
				if ( ( ! $conn_id ) || ( ! $login_result ) ) {
					echo json_encode( $result );
					exit;
				}

				// turn passive mode on
				ftp_pasv( $conn_id, true );

				$path_server = '/public_html/uploads/' . $shop_id . '/';

				if ( ! @ftp_chdir( $conn_id, $path_server ) ) {
					ftp_mkdir( $conn_id, $path_server );
				}

				// upload a file to server
				$uploads = ftp_put( $conn_id, $path_server . $image_name, $file['tmp_name'], FTP_BINARY );
				if ( $uploads ) {
					$path_thumb = $path_server . '_thumb/';
					if ( ! @ftp_chdir( $conn_id, $path_thumb ) ) {
						ftp_mkdir( $conn_id, $path_thumb );
					}

					//copy image thumb from storage to server
					$uploads_thumb = ftp_put( $conn_id, $path_thumb . $image_name, $path . $image_name, FTP_BINARY );
					if ( $uploads_thumb ) {

						// delete thumb image in storage
						Storage::delete( 'public/uploads/' . $shop_id . '/_thumb/' . $image_name );

						$result = array(
							'status'          => 'success',
							'image_url'       => 'https://imgs.fireapps.vn/uploads/' . $shop_id . '/' . $image_name,
							'image_thumb_url' => 'https://imgs.fireapps.vn/uploads/' . $shop_id . '/_thumb/' . $image_name,
							'image_name'      => $image_name,
						);
					}
				}

				// close the connection
				ftp_close( $conn_id );
			}

		}

		echo json_encode( $result );
		exit();
	}

	/**
	 * Ajax delete file
	 *
	 * @param Request $request
	 */
	public function deleteFile( Request $request ) {
		$result = array( 'status' => 'error', 'message' => Lang::get( 'reviews.fail' ) );
		if ( $request->ajax() ) {
			$data = $request->all();
			if ( ! empty( $data['image_name'] ) ) {
				$shopId      = session( 'shopId' );
				$path_server = '/public_html/uploads/' . $shopId . '/';

				/**
				 * ftp upload image to server imgs.fireapps.vn
				 */
				// set up basic connection
				$conn_id = ftp_connect( config( 'common.ftp_host' ) );
				// login with username and password
				$login_result = ftp_login( $conn_id, config( 'common.ftp_user' ), config( 'common.ftp_pass' ) );
				if ( ( ! $conn_id ) || ( ! $login_result ) ) {
					echo json_encode( $result );
					exit;
				}

				// turn passive mode on
				ftp_pasv( $conn_id, true );

				$delete = ftp_delete( $conn_id, $path_server . $data['image_name'] );
				if ( $delete ) {
					$delete_thumb = ftp_delete( $conn_id, $path_server . '_thumb/' . $data['image_name'] );
					if ( $delete_thumb ) {
						$result = array( 'status' => 'success' );
					}
				}
				// close the connection
				ftp_close( $conn_id );
			}

		}
		echo json_encode( $result );
		exit();
	}

	public function addGoogleRating(Request $request)
	{
		$productId = $request->input('product_id');
		$shopId = session('shopId');
		$shopDomain = session('shopDomain');
		$accessToken = session('accessToken');

		event( new AddGoogleRatingEvent($shopId,$shopDomain,$accessToken,$productId));

		return response()->json([
			'status' => 'success',
			'message' => "It'll take 10-15sec to update your setting. Please wait!"
		], 200);
	}


    /**
     * @param string $shopId
     * @param string $productId
     * @param array $unCheckId
     * @param $typePage
     * @param $urlParam
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleDeleteMultiReview($shopId = '', $productId = '', $unCheckId = [], $typePage , $urlParam){

            $result = $this->_commentRepo->deleteAllCommentReviews($shopId, $productId,$unCheckId ,$typePage ,$urlParam);

            $statusCode = $result['status'] === 'success' ? 200 : 500;

            return response()->json($result, $statusCode);
    }


    /**
     * @author thachleviet
     * @param string $shopId
     * @param string $productId
     * @param array $unCheckId
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleUnPublishMultiReview($shopId, $productId, $unCheckId, $typePage , $urlParam)
    {

            $result = $this->_commentRepo->updateAllCommentReviews(
                $shopId,
                $productId,
                ['status' => '0'], $unCheckId,
                $typePage,
                $urlParam
            );

            $statusCode = $result['status'] === 'success' ? 200 : 500;

            return response()->json($result, $statusCode);

    }

    /**
     * @param string $shopId
     * @param string $productId
     * @param array $unCheckId
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePublishMultiReview($shopId, $productId, $unCheckId, $typePage , $urlParam)

    {

		$result = $this->_commentRepo->updateAllCommentReviews(
			$shopId,
			$productId,
			['status' => '1'], $unCheckId,
			$typePage,
			$urlParam
		);

		$statusCode = $result['status'] === 'success' ? 200 : 500;

		return response()->json($result, $statusCode);
    }

}
