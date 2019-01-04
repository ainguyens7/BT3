<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use App\Jobs\DeleteReviewsProductJob;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Exception;
use App\Events\BeforeImportReviewEvent;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller {

	private $_shopRepo;
	private $_shopMetaRepo;
	private $_productRepo;
	private $_commentRepo;

	private $sentry;

	public function __construct(RepositoryFactory $factory) {
		$this->_productRepo  = $factory::productsRepository();
		$this->_shopRepo     = $factory::shopsReposity();
		$this->_shopMetaRepo = $factory::shopsMetaReposity();
		$this->_commentRepo = $factory::commentBackEndRepository();
		$this->sentry = app('sentry');
	}

	/**
	 * Get list countries
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCountries()
	{
		$allCountryCode = Helpers::getCountryCode();

		return response()->json($allCountryCode);
	}

	/**
	 * Get settings of shop
	 *
	 * @param $shopName
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getSettings($shopName,Request $request)
	{

		$shop = $this->_shopRepo->detail(['shop_name' => $shopName]);

		$result = array(
			'status' => 'error',
			'message' => trans('api.message_error_shop_install'),
			'url' => 'https://apps.shopify.com/ali-reviews'
		);

		if($shop['status']){
			$shopInfo = $shop['shopInfo'];
			$shopId = $shopInfo->shop_id;

			if($shopInfo->app_version != config('common.app_version')){
				$result = array(
					'status' => 'error',
					'message' => trans('api.update_app'),
					'url' => url('update'),
				);
			}else{
				$planInfo = config('plans.'.$shopInfo->app_plan);
				if(!$planInfo['add_from_operlo']){
					$result = array(
						'status' => 'error',
						'message' => trans('api.only_unlimited_plan'),
						'url' => route('apps.upgrade'),
					);
				}else{
					$settings = Helpers::getSettings($shopId);
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

					// Chưa fix từ func root Helpers::getSettings vì liên quan nhiều Controller khác
					$pictureOption = [];
					$contentOption = [];
					if (!empty($settings['setting'])) {
							if (array_key_exists('get_only_picture', $settings['setting'])) {
									$picSetting = $settings['setting']['get_only_picture'];
                                    $pictureOption = is_array($picSetting) ? ((in_array("1",$picSetting)) ? ["true", "false"] :  $picSetting ): ( $picSetting === "all"  ? ["true", "false"] : [$picSetting] );
							}
							
							if (array_key_exists('get_only_content', $settings['setting'])) {
									$contentSetting = $settings['setting']['get_only_content'];
                                    $contentOption = is_array($contentSetting) ? ((in_array("1",$contentSetting)) ? ["true", "false"] :  $contentSetting )  : [$contentSetting];
							}
							if (!array_key_exists('get_only_star', $settings['setting']) || !isset($settings['setting']['get_only_star'])) {
								$settings['setting']['get_only_star'] = ["1", "2", "3", "4", "5"];
							}
							$settings['setting']['get_only_picture'] = $pictureOption;
							$settings['setting']['get_only_content'] = $contentOption;
					}

					$settings = array_merge($settings, [
						'ali_languages_site' => config('ali_languages_site')
					]);

					$result = array(
						'status' => 'success',
						'data' => $settings,
					);
				}
			}
		}

		return response()->json($result);
	}

	/**
	 * get product info
	 *
	 * @param $shopName
	 * @param $productId
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getProductInfo($shopName,$productId,Request $request){
		$shop = $this->_shopRepo->detail(['shop_name' => $shopName]);
		if($shop['status']) {
			$shopId = $shop['shopInfo']->shop_id;
            $importedReview =  $this->_commentRepo->findImportedReview($shopId,$productId) ;
            if($importedReview['status']){
                $product = $this->_productRepo->detail( $shopId, $productId );
                if(!empty($product)){
                    $product->url_manage_review = route('reviews.product',['productId' => $productId]);
                    $product->url_dashboard = url('');
                }
                $product->is_reviews = ((int)$importedReview['result'] > 0 ) ? '1' : '0';
                return response()->json($product);
            }else{
                return response()->json($importedReview);
            }
		}
	}

    /**
     * Prepare import review event
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepareImportReview(Request $request)
    {
        $data = $request->all();
        if (empty($data['shopId'])) {
            return response()->json([
                'status' => false,
                'message' => 'Wrong request parameters'
            ]);
        }

        event(new BeforeImportReviewEvent($data['shopId']));
        return response()->json([
            'status' => true,
            'message' => 'Prepare import review success'
        ]);
    }

	/**
	 * Save reviews for product
	 *
	 * @param $shopName
	 * @param $productId
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function saveReviews($shopName, $productId, Request $request)
	{
		$data = $request->json()->all();
		
		$this->sentry->user_context([
			'shop_name' => $shopName,
			'product_id' => $productId,
			'request' => $data
		]);

		try {
			$data['reviewObj'] = json_decode($data['reviewObj']);

			if (empty($data['reviewObj'])) {
				return response()->json([
					'status' => false,
					'message' => 'Cannot reviews obj'
				]);
			}

			$shop = $this->_shopRepo->detail(['shop_name' => $shopName]);
			if ($shop['status']) {
				$shopId = $shop['shopInfo']->shop_id;
                event(new BeforeImportReviewEvent($shopId));
			} else {
				return response()->json([
					'status' => false,
					'message' => 'Shop Name Invalid'
				]);
			}

			$type = $data['type'];

			$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);

			if(!empty($shopPlanInfo['status'])) {
				$shopPlanInfo = $shopPlanInfo['planInfo'];
			}

			$this->sentry->user_context([
				'type' => $type,
				'shop_plan_info' => $shopPlanInfo
			]);

 			if ($request->has('source')) {
 				$source = $request['source'];
 			} else {
 				$source = 'oberlo';
 			}

			$shouldSaveReview = $this->_commentRepo->saveObjReviewAliexpress(
				$shopId,
				$productId,
				$data['reviewObj'],
				$type,
				$shopPlanInfo,
                $source
			);

			if(!$shouldSaveReview) {
				return response()->json([
					'status' => false,
					'message' => 'Cannot save review to database',
				]);
			}

			//Update product table
			$this->_productRepo->update(
				$shopId, 
				$productId, 
				['is_reviews' => config('common.is_reviews.reviews')]
			);

			$avgReviews = Helpers::getAvgStarInObjectRating($data['reviewObj']);
			$totalReviews = count($data['reviewObj']);

			return response()->json([
				'status' => true,
				'totalReviews' => $totalReviews,
				'avgReviews' => $avgReviews,
				'url' => route('reviews.product', $productId),
			]);

		} catch (Exception $ex) {
			$sentryId = $this->sentry->captureException($ex);
			return response()->json([
				'status' => false,
				'message' => "Cannot save review to database. EventId: {$sentryId}",
			]);
		}		
	}

	// /**
	//  * Delete all review in one product
	//  *
	//  * @param $shopId
	//  * @param $productId
	//  *
	//  * @return array
	//  */

	// public function deleteAllReviewsOneProduct( $shopName, $productId)
	// {
	// 	$job = new DeleteAllReviewsOneProduct($shopName, $productId);
	// 	dispatch($job);
	// }

	// public function deleteAllReviewsManyProducts($shopName, $productIds)
	// {
	// 	$job = new DeleteAllReviewsManyProducts($shopName, $productIds);
	// 	dispatch($job);
	// }

	/**
	 * Delete reviews of product
	 * @param String $shop
	 * @param Array|String productIds
	 * @return message
	 */

	 public function delReviewsProduct(Request $request)
	 {
		$sentry = app('sentry');
		// set user context
		$sentry->user_context([
			'request' => $request->all()
		]);

		try {
			$rawShop = $request->json('shop', '');
			$rawProductId = $request->json('productId', '');
			
			$shop = $this->_shopRepo->detail(['shop_name' => $rawShop]);

			$result = array(
				'status' => 'error',
				'message' => trans('api.message_error_shop_install'),
				'url' => 'https://apps.shopify.com/ali-reviews'
			);

			if (!$rawShop || !$rawProductId) {
				$result['status'] = 'error';
				$result['message'] = 'Missing parameters';
				return response()->json($result, 400);
			}
			
			$shop = $this->_shopRepo->detail(['shop_name' => $rawShop]);

			if (!$shop['status']) {
				$result['status'] = 'error';
				$result['message'] = 'Unauthorized';
				return response()->json($result, 401);
			}
			
			$shopInfo = $shop['shopInfo'];
			$shopId = $shopInfo->shop_id;

			if($shopInfo->app_version != config('common.app_version')) {
				$result = array(
					'status' => 'error',
					'message' => trans('api.update_app'),
					'url' => url('update'),
				);
				return response()->json($result, 200);
			}

			$planInfo = config('plans.'.$shopInfo->app_plan);

			if (!$planInfo['add_from_operlo']) {
				$result = array(
					'status' => 'error',
					'message' => trans('api.only_unlimited_plan'),
					'url' => route('apps.upgrade'),
				);
				return response()->json($result, 200);
			}

			$typeProductId = gettype($rawProductId);

			$result['status'] = 'success';
			$result['message'] = 'Reviews was schedule to delete';
			$result['url'] = '';

			switch ($typeProductId) {
				case 'array':
					dispatch(new DeleteReviewsProductJob($shopId, $rawProductId));
					return response()->json($result, 200);
					break;
				case 'string':
					dispatch(new DeleteReviewsProductJob($shopId, [$rawProductId]));
					return response()->json($result, 200);
					break;
				default: 
					$result['status'] = false;
					$result['message'] = 'No reviews was scheduled';
					return response()->json($result, 200);
					break;
			}
		} catch (Exception $ex) {
			$eventId = $sentry->captureException($ex);
			$result['status'] = false;
			$result['message'] = "Internal error. EventID: {$eventId}";
			return response()->json($result, 200);
		}
	 }

    /**
     * Get review box
     *
     * @param String $shopName
     * @param String $productId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewBox($shopName, $productId)
    {
		// $shop = $this->_shopRepo->detail(['shop_name' => $shopName]);
        // if ($shop['status']) {
		// 	$shopId = $shop['shopInfo']->shop_id;
		// 	$result = (new ReviewService($shopId, $productId))->generateReviewBox();
        //     if (!$result['status']) {
        //         return response()->json($result, 200);
        //     }
        //     return response()->json($result, 200);
        // }

        // $result = [
        //     'status' => false,
        //     'message' => 'Wrong parameter'
        // ];
        // return response()->json($result, 200);
    }

    /**
     * Get review badge
     *
     * @param String $shopName
     * @param String $productId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewBadge($shopName, $productId)
    {
		// $shop = $this->_shopRepo->detail(['shop_name' => $shopName]);
        // if ($shop['status']) {
        //     $shopId = $shop['shopInfo']->shop_id;
        //     $result = (new ReviewService($shopId, $productId))->generateBadgeReview();
        //     return response()->json($result, 200);
        // }

        // $result = [
        //     'status' => false,
        //     'message' => 'Wrong parameter'
        // ];
        // return response()->json($result, 200);
	}
	
	public function bulkExistReview($shopName, Request $request)
	{
		$productIdList = $request->json('product_id_list', []);

		$result = [
			'status' => false,
			'result' => trans('api.bad_request_parameter')
		];

		$shop = $this->_shopRepo->detail(['shop_name' => $shopName]);

		if(!$shop['status']) {

			$result['result'] = trans('api.message_internal');
			return response()->json($result, 200);
		}

		if (empty($productIdList)) {
			return response()->json($result);
		}

		$shopId = $shop['shopInfo']->shop_id;

		$productResult = $this->_productRepo->bulkDetail($shopId, $productIdList);

		if(is_null($productResult)){
			$result['status'] = false;
			$result['result'] = trans('api.message_internal');
			return response()->json($result, 200);
		}

		$result['status'] = true;

		$productMap = array_map(function($item) use($shopId){
			$urlManageReview = route('reviews.product',['productId' => $item->id]);
            $importedReview  = $this->_commentRepo->findImportedReview($shopId, $item->id) ;
            if($importedReview['status']){
                return (object) [
                    'id' => $item->id,
                    'is_reviews' => ((int)$importedReview['result'] > 0 ) ? '1' : '0',
                    'url_manage_review' => $urlManageReview
                ];
            }else{
                return (object)$importedReview;
            }
		}, $productResult->toArray());

		$result['result'] = $productMap;

		return response()->json($result);
	}
	public function healthyCheckApp()
	{
		try {
			// get alireviews connection
			$connectMain = DB::connection('mysql')->getPdo();
			// get alireviews product connection
			$connectProduct = DB::connection('mysql_product')->getPdo();
			$status = true;
		} catch (\Exception $ex) {
			$this->sentry->captureException($ex);
			$status = false;
		}
		if (!$status) {
			return response()->json([], 503);
		}
		return response()->json([], 200);
	}

    /**
     * @param $shopName
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoShop($shopName){

        $sentry = app('sentry');
        // set user context
        $sentry->user_context([
            'shop_name' =>$shopName
        ]);

        try{
            $shop = $this->_shopRepo->detail(['shop_name' => $shopName]);

            if ($shop['status']) {
                $shop['shopInfo']['url_pricing'] = url('pricing');
                $shop['shopInfo']['url_origin']  = url('');
                return response()->json(['status'=>true , 'result'=>$shop['shopInfo']]);
            }

            $result['status'] = 'error';

            $result['message'] = __('api.shop_not_exits');

            return response()->json($result);

        }catch (\Exception $exception){

             $eventId = $this->sentry->captureException($exception);

             return response()->json(['status' => false, 'message'=> "{$exception->getMessage()}. EventId: {$eventId}"]);
        }

    }


    public function getSettingsExtensionAliOrder($shopName)
    {

        $shop = $this->_shopRepo->detail(['shop_name' => $shopName]);

        $result = array(
            'status' => 'error',
            'message' => config('api.message_error_shop_install'),
            'url' => 'https://apps.shopify.com/ali-reviews'
        );

        if($shop['status']){
            $shopInfo = $shop['shopInfo'];
            $shopId = $shopInfo->shop_id;

            if($shopInfo->app_version != config('common.app_version')){
                $result = array(
                    'status' => 'error',
                    'message' => config('api.update_app'),
                    'url' => "https://alireviews.fireapps.io/update",
                );
            }else{

                $settings = Helpers::getSettings($shopId);
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

                // Chưa fix từ func root Helpers::getSettings vì liên quan nhiều Controller khác
                $pictureOption = [];
                $contentOption = [];
                if (!empty($settings['setting'])) {
                    if (array_key_exists('get_only_picture', $settings['setting'])) {
                        $picSetting = $settings['setting']['get_only_picture'];
                        $pictureOption = is_array($picSetting) ? ((in_array("1",$picSetting)) ? ["true", "false"] :  $picSetting ): ( $picSetting === "all"  ? ["true", "false"] : [$picSetting] );
                    }

                    if (array_key_exists('get_only_content', $settings['setting'])) {
                        $contentSetting = $settings['setting']['get_only_content'];
                        $contentOption = is_array($contentSetting) ? ((in_array("1",$contentSetting)) ? ["true", "false"] :  $contentSetting )  : [$contentSetting];
                    }
                    if (!array_key_exists('get_only_star', $settings['setting']) || !isset($settings['setting']['get_only_star'])) {
                        $settings['setting']['get_only_star'] = ["1", "2", "3", "4", "5"];
                    }
                    $settings['setting']['get_only_picture'] = $pictureOption;
                    $settings['setting']['get_only_content'] = $contentOption;
                }

                $settings = array_merge($settings, [
                    'ali_languages_site' => config('ali_languages_site')
                ]);

                $result = array(
                    'status' => 'success',
                    'data' => $settings,
                );

            }
        }

        return response()->json($result);
    }
}
