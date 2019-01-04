<?php

namespace App\Http\Controllers;

use App\Events\AddGoogleRatingEvent;
use App\Events\UserLikeMetaField;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\SendMailFrontendReviews;
use App\Jobs\UpdateProductMetafieldJob;
use App\Repository\CommentFrontEndRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Services\GeoIpService;
use App\Events\CustomerLikeAction;

class ReviewsFrontendController extends Controller {

	private $_commentRepo;
	private $_commentInfoRepo;
	private $_commentDefaultRepo;
	private $_shopMetaRepo;
	private $_shopRepo;
	private $_commentBERepo;

	function __construct(RepositoryFactory $factory) {
		$this->_commentRepo = $factory::commentFrontEndRepository();
		$this->_commentBERepo  = $factory::commentBackEndRepository();
		$this->_shopRepo = $factory::shopsReposity();
		$this->_shopMetaRepo = $factory::shopsMetaReposity();
		$this->_commentDefaultRepo = $factory::commentsDefaultRepository();
		$this->_commentInfoRepo = $factory::commentInfoRepository();
	}

	public function getReview(Request $request) {
		$req = $request->all();

		if (!isset($req['shop_id']) || !isset($req['product_id'])) {
			return response()->json(['status' => false, 'message' => 'parameter is invalid!']);
		}

		$shop_id = $req['shop_id'];
		$settings = Helpers::getSettings($shop_id);
		if(empty($settings['active_frontend']))
			return response()->json(['status' => false, 'message' => 'No active front-end!']);

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		if ($shopPlanInfo['status']) {
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		}
		$product_id = $req['product_id'];
		$ip = Helpers::getProxyClientIp();
		$comment_repo = new CommentFrontEndRepository();

		$products_not_in = array();
		if(!empty($req['products_not_in'])){
			$products_not_in = explode(',',$req['products_not_in']);
		}


		$star = 'all';
		if(!empty($req['star'])){
			$star = $req['star'];
		}

		$sort_type  = isset($req['sort_type']) ? $req['sort_type'] : null ;


		if (isset($req['currentPage'])) {

			$currentPage = $req['currentPage'];

			$data_obj = $this->_commentRepo->getReviewProductFrontEnd($product_id, $shop_id, $currentPage,$ip,$products_not_in ,$sort_type ,$star);
		} else {

			$data_obj = $this->_commentRepo->getReviewProductFrontEnd( $product_id, $shop_id, null, $ip, $products_not_in ,$sort_type, $star);

		}

		$limit = 0;
		$avg_star = $comment_repo->getAvgStar($product_id, $shop_id);
		$total_review = $this->_commentRepo->getTotalStar($product_id, $shop_id, null, [config('common.status.publish')]);
		/*if(empty($data_obj->total()) && !empty($settings['is_comment_default']) && !empty($shopPlanInfo['sample_reviews'])){
			$perPage  = $settings['setting']['max_number_per_page'];

			if(!empty($req['num_rand'])){
				$limit = $req['num_rand'];
			}else{
				if(!empty($settings['rand_comment_default'])){
//				return response()->json($settings['rand_comment_default']) ; exit();
					$from = $settings['rand_comment_default']['from'];
					$to = $settings['rand_comment_default']['to'];
					$limit = rand($from,$to);
				}
			}

			$currentPage = 1;
			if (isset($req['currentPage'])) {
				$currentPage = $req['currentPage'];
			}


			$list_random_reviews = $this->_commentDefaultRepo->allFrontEnd($shop_id,[
				'limit' => $limit,
				'perPage' => $perPage,
				'currentPage' => $currentPage,
			]);

			$data_obj = $list_random_reviews['data'];
			$total_review = $list_random_reviews['total'];
			$avg_star = 5;
		}else{
			$avg_star = $comment_repo->getAvgStar($product_id, $shop_id);
			$total_review = $this->_commentRepo->getTotalStar($product_id, $shop_id, null, [config('common.status.publish')]);
		}*/

		//Get show meta
		$style = 2;
		$setting = isset($settings['setting']) ? ($settings['setting']) : [];
		if (empty($setting['section_show'])) {
			$setting['section_show'] = array();
		}
		$approve_review = isset($settings['approve_review']) ? $settings['approve_review'] : 1;
		$translate = isset($settings['translate']) ? $settings['translate'] : config('settings.translate');
//		if(empty($settings['is_translate'])){
//			$translate = config('settings.translate');
//		}
		if (isset($translate['label_image']) && $translate['label_image'] === 'Image') {
			$translate['label_image'] = config('settings.translate.label_image');
		}
		$rating_card = isset($settings['rating_card']) ? $settings['rating_card'] : config('settings.rating_card');
		$rating_point = isset($settings['rating_point']) ? $settings['rating_point'] : config('settings.rating_point');

		$code_css = false;
		$style_customize = false;
		if(!empty($shopPlanInfo['custom_style'])){
			$style_customize = isset($settings['style_customize']) ? $settings['style_customize'] : config('settings.style_customize');
			$code_css = isset($settings['code_css']) ? $settings['code_css'] : config('settings.code_css');
		}

		$view_part = 'comment.style' . $style . '.listReview';

		//Check version app if different not show
		/*$objectShop = $this->_shopRepo->detail( array( 'shop_id' => $shop_id ) );
		if ( $objectShop['status'] ) {
			$shop = $objectShop['shopInfo'];
			if ( $shop->app_version != config( 'common.app_version' ) ) {
				return response()->json( [ 'status' => false, 'message' => 'Please update version' ] );
			}
		}*/
		$statistic = $this->getStatisticReview($product_id, $shop_id);
		//return response()->json($args_views);
		if ($data_obj) {
			$args_views = [
				'comments' => $data_obj,
				'total_review' => !empty($total_review) ? $total_review : 0,
				'avg_star' => $avg_star,
				'setting' => $setting,
				'translate' => $translate,
				'shop_id' => $shop_id,
				'product_id' => $product_id,
				'approve_review' => $approve_review,
				'shopPlanInfo' => $shopPlanInfo,
				'code_css' => $code_css,
				'style_customize' => $style_customize,
				'rating_point' => $rating_point,
				'rating_card' => $rating_card,
				'statistic' => $statistic
			];
			$view = view($view_part, $args_views)->render();

			$text_reviews = config('settings')['translate']['text_review_title'];
			if(!empty($translate['text_reviews_title'])){
				$text_reviews = $total_review.' '.$translate['text_reviews_title'];
			}
			if (intval($total_review) === 1) {
				$text_reviews = isset($translate['text_review_title']) ? $total_review . ' ' . $translate['text_review_title']: config('settings')['translate']['text_review_title'];
			}
			if (intval($total_review) === 0) {
				$text_reviews = '';
			}

			return response()->json([
				'status' => true,
				'limit' => $limit,
				'star' => $star,
				'avg' => $avg_star,
				'rating_point' => $rating_point,
				'total_review' => !empty($total_review) ? $total_review : 0,
				'view' => $view,
                'data' => $data_obj,
				'text_reviews' => $text_reviews,
                'sort_type'=>[
                    'all'=> (!empty($sort_type) && $sort_type === 'all')  ? true : false,
                    'date'=>(!empty($sort_type) && $sort_type === 'date')  ? true : false,
					'stars'=>(!empty($sort_type) && $sort_type === 'stars')  ? true : false,
					'content'=> (!empty($sort_type) && $sort_type === 'content')  ? true : false,
                    'pictures'=> (!empty($sort_type) && $sort_type === 'pictures')  ? true : false
                ]
			]);
		} else {
			return response()->json(['status' => false, 'message' => 'Cannot find review']);
		}

	}

	/**
	 * Get statistic review for product
	 *
	 * @param $productId
	 *
	 * @return array
	 */
	public function getStatisticReview($productId, $shop_id) {
		$shopId    = $shop_id;
		$statistic = array();

		$statistic['avg_star']     = $this->_commentBERepo->getAvgStar( $productId, $shopId );
		$statistic['total_star']   = $this->_commentBERepo->getTotalStar( $productId, $shopId );
		$statistic['total_star_5'] = $this->_commentBERepo->getTotalStar( $productId, $shopId, 5 );
		$statistic['total_star_4'] = $this->_commentBERepo->getTotalStar( $productId, $shopId, 4 );
		$statistic['total_star_3'] = $this->_commentBERepo->getTotalStar( $productId, $shopId, 3 );
		$statistic['total_star_2'] = $this->_commentBERepo->getTotalStar( $productId, $shopId, 2 );
		$statistic['total_star_1'] = $this->_commentBERepo->getTotalStar( $productId, $shopId, 1 );
		if ( $statistic['total_star'] > 0 ) {
			$statistic['percent_star_5'] = ( $statistic['total_star_5'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_4'] = ( $statistic['total_star_4'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_3'] = ( $statistic['total_star_3'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_2'] = ( $statistic['total_star_2'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_1'] = ( $statistic['total_star_1'] * 100 ) / $statistic['total_star'];
		}

		$statistic['total_reviews'] = $this->_commentBERepo->getTotalStatus( $productId, $shopId );

		return $statistic;
	}

	public function validateForm($res) {
		$settings = Helpers::getSettings($res['shop_id']);
		$translate = $settings['translate'];
		if(empty($settings['is_translate'])){
			$translate = config('settings.translate');
		}

		$error_maximum_characters = isset($translate['error_maximum_characters']) ? $translate['error_maximum_characters'] : config('settings.translate.error_maximum_characters');

		$err = [
			'author' => '',
			'content' => '',
			'star' => '',
		];

		if (strlen($res['author']) >= 50) {
			$err['author'] = str_replace('{number}', 50, $error_maximum_characters);
		}

		if ($res['author'] == '') {
			$err['author'] = $translate['error_required'];
		}

		if (!empty($res['email']) && !filter_var($res['email'], FILTER_VALIDATE_EMAIL)) {
			$err['email'] = $translate['error_email'];
		}

		if (strlen($res['content']) <= 0) {
			$err['content'] = $translate['error_required'];
		} elseif (strlen($res['content']) >= 2000) {
			$err['content'] = str_replace('{number}', 2000, $error_maximum_characters);
		}

		if (strlen($res['star'] <= 0) || strlen($res['star'] > 5)) {
			$err['star'] = $translate['error_required'];
		}

		return $err;
	}

	public function postReview(Request $request) {
		$ip = Helpers::getProxyClientIp();
		$geoData = GeoIpService::GetGeoIp($ip);
		$productId = $request->input('alireview_product_id', '');
		Helpers::saveLog('info', [
			'message' => 'Ip from x-shopify-client-ip: ' . $ip
		]);
		Helpers::saveLog('info', [
			'message' => 'Found geo data: ' . var_export($geoData, true)
		]);

		$data = $request->input();
		$data['alireview_country_code'] = $geoData['country_code'];
		$res = [];
		// $alireview_img = '';
		// if (!empty($data['alireview_img']) && is_array($data['alireview_img'])) {
		// 	$alireview_img = $data['alireview_img'];
		// 	unset($data['alireview_img']);
		// }

		if(!empty($data[$this->_commentRepo->getPrefix().'shop_id'])){
			$shopId = $data[$this->_commentRepo->getPrefix().'shop_id'];
			$settings = Helpers::getSettings($shopId);

			$data['alireview_country_code'] = $geoData['country_code'];
			$res = [];
			$alireview_img = '';
			if (!empty($data['alireview_img']) && is_array($data['alireview_img'])) {
				$alireview_img = $data['alireview_img'];
				unset($data['alireview_img']);
			}

			foreach ($data as $k => $v) {
				if (in_array($k, $this->_commentRepo->fillableFrontend())) {
					$res[str_replace($this->_commentRepo->getPrefix(), '', $k)] = trim($v);
				}
			}

			$err = $this->validateForm($res);
			//Validate form
			if (!empty(array_filter($err))) {
				return response()->json(['status' => false, 'err' => $err]);
			}

			// set img
			$res['img'] = json_encode($alireview_img);

			$approveReview = 0;
			if ( $settings['approve_review'] == 1) {
				$approve_review_stars = config('settings.setting.approve_review_stars');
				if(!empty($settings['setting']['approve_review_stars'])){
					$approve_review_stars = $settings['setting']['approve_review_stars'];
				}
				if(!empty($res['star']) && in_array(intval($res['star']),$approve_review_stars)){
					$approveReview = 1;
				}
			}

			//edit status review
			if ($approveReview == 1) {
				$res['status'] = config('common.status.publish');
			}

			$translate = $settings['translate'];
			if(empty($settings['is_translate'])){
				$translate = config('settings.translate');
			}

			$last_review = $this->_commentRepo->saveForm($res);
			if (!$last_review) {
				return response()->json([
					'status' => false,
					'error' => $translate['error_add_fail'],
				]);
			}


			if (empty($approveReview)) {
				//Send mail if success
				$objectShop = $this->_shopRepo->detail(array('shop_id' => $res['shop_id']));
				if ($objectShop['status']) {
					$shop = $objectShop['shopInfo'];
					// $job = new UpdateProductMetafieldJob($res['shop_id'], $shop->shop_name, $shop->access_token, $productId);
					// $this->dispatch($job);
					$this->dispatch(new SendMailFrontendReviews($shop, $res));
				}

			}

			// if ($approveReview == 1) {
			// 	$message_success = isset($translate['message_thanks_has_approve']) ?$translate['message_thanks_has_approve'] : config('settings.translate.message_thanks_has_approve');
			// }

			$message_success = isset($translate['message_thanks_has_approve']) ? $translate['message_thanks_has_approve']: config('settings.translate.message_thanks_has_approve');

			return response()->json(['status' => true,
			                         'message' => $message_success,
			                         'approve_review' => $approveReview]);
		}
		return response()->json(['status' => false,
		                         'message' => 'Error',
		                         'approve_review' => 0]);
	}

	/**
	 * Get summary review in collection product
	 *
	 * @param Request $request
	 *
	 * @author: Bui Cong Dang <bdangvn@gmail.com>
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getSummaryStarCollection(Request $request) {
		$product_ids = $request->input('product_ids');
		$shop_domain = $request->input('shop_domain');

		$total_review = array();
		$avg_star = array();

		//Get shop info
		$objectShop = $this->_shopRepo->detail(array('shop_name' => $shop_domain));
		if ($objectShop['status']) {
			$shop = $objectShop['shopInfo'];
			$shop_id = $shop->shop_id;

			$settings = Helpers::getSettings($shop_id);
			$text_review_plural = !empty($settings['translate']['text_reviews_title']) ? $settings['translate']['text_reviews_title'] : 'reviews';
			$text_review_singular = !empty($settings['translate']['text_review_title']) ? $settings['translate']['text_review_title'] : 'review';

			$rating_point = isset($settings['rating_point']) ? $settings['rating_point'] : config('settings.rating_point');

			if(!empty($product_ids)){
				foreach ($product_ids as $key => $value) {
					$total_review[$value] = $this->_commentRepo->getTotalStar($value, $shop_id, null, [config('common.status.publish')]);
					$avg_star[$value] = $this->_commentRepo->getAvgStar($value, $shop_id);
				}
			}

			return response()->json(['avg_star' => $avg_star, 'total_review' => $total_review,'rating_point' => $rating_point, 'text_review_plural' => $text_review_plural, 'text_review_singular' => $text_review_singular]);
		}
	}

	/**
	 * ajax upload images
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function uploadImage(Request $request) {
		$result = array('status' => 'error', 'message' => "An error, please try again.");
		$shop_id = $request['alireview_shop_id'];

		if (!empty($shop_id) && $_FILES) {
			$file = array_shift($_FILES);
			$image_name = time() . str_random(5) . md5($file['name']) . '.jpg';
			list($width, $height) = getimagesize($file['tmp_name']);
			$path = storage_path('app/public/uploads/' . $shop_id . '/_thumb/');
			File::makeDirectory($path, $mode = 0777, true, true);

			// resize image to thumb image in storage
			if ($width > $height) {
				$resize = Image::make($file['tmp_name'])
				               ->resize(null, 300, function ($constraint) {$constraint->aspectRatio();})
				               ->crop(300, 300)->save($path . $image_name);
			} else {
				$resize = Image::make($file['tmp_name'])
				               ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
				               ->crop(300, 300)->save($path . $image_name);
			}

			if ($resize) {
				/**
				 * ftp upload image to server imgs.fireapps.vn
				 */
				// set up basic connection
				$conn_id = ftp_connect(config('common.ftp_host'));
				// login with username and password
				$login_result = ftp_login($conn_id, config('common.ftp_user'), config('common.ftp_pass'));
				if ((!$conn_id) || (!$login_result)) {
					return response()->json($result);
				}

				// turn passive mode on
				ftp_pasv($conn_id, true);

				$path_server = '/public_html/uploads/' . $shop_id . '/';

				if (!@ftp_chdir($conn_id, $path_server)) {
					ftp_mkdir($conn_id, $path_server);
				}

				// upload a file to server
				$uploads = ftp_put($conn_id, $path_server . $image_name, $file['tmp_name'], FTP_BINARY);
				if ($uploads) {
					$path_thumb = $path_server . '_thumb/';
					if (!@ftp_chdir($conn_id, $path_thumb)) {
						ftp_mkdir($conn_id, $path_thumb);
					}

					//copy image thumb from storage to server
					$uploads_thumb = ftp_put($conn_id, $path_thumb . $image_name, $path . $image_name, FTP_BINARY);
					if ($uploads_thumb) {

						// delete thumb image in storage
						Storage::delete('public/uploads/' . $shop_id . '/_thumb/' . $image_name);

						$result = array(
							'status' => 'success',
							'image_url' => 'https://imgs.fireapps.vn/uploads/' . $shop_id . '/' . $image_name,
							'image_thumb_url' => 'https://imgs.fireapps.vn/uploads/' . $shop_id . '/_thumb/' . $image_name,
							'image_name' => $image_name,
						);
					}
				}

				// close the connection
				ftp_close($conn_id);
			}
		}

		return response()->json($result);

//		echo json_encode($result) ; exit();
	}

	/**
	 * Ajax delete image
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImage(Request $request) {
		$result = array('status' => 'error', 'message' => "An error, please try again.");

		$data = $request->all();
		if (!empty($data['alireview_shop_id']) && !empty($data['image_name'])) {
			$shopId = $data['alireview_shop_id'];
			$path_server = '/public_html/uploads/' . $shopId . '/';

			/**
			 * ftp upload image to server imgs.fireapps.vn
			 */
			// set up basic connection
			$conn_id = ftp_connect(config('common.ftp_host'));
			// login with username and password
			$login_result = ftp_login($conn_id, config('common.ftp_user'), config('common.ftp_pass'));
			if ((!$conn_id) || (!$login_result)) {
				echo json_encode($result);exit;
			}

			// turn passive mode on
			ftp_pasv($conn_id, true);

			$delete = ftp_delete($conn_id, $path_server . $data['image_name']);
			if ($delete) {
				$delete_thumb = ftp_delete($conn_id, $path_server . '_thumb/' . $data['image_name']);
				if ($delete_thumb) {
					$result = array('status' => 'success');
				}

			}
			// close the connection
			ftp_close($conn_id);
		}

		return response()->json($result);
	}

	public function like(Request $request) {
		$result = array('status' => 'error', 'message' => "An error, please try again.");
		$data = $request->all();
		$data['ip'] = Helpers::getProxyClientIp();

		if (!empty($data['comment_id']) && !empty($data['shop_id']) && !empty($data['ip'])) {
			$checkIpLike = $this->_commentInfoRepo->all($data['shop_id'], [
				'comment_id' => $data['comment_id'],
				'ip_like' => $data['ip'],
				'perPage' => 1,
			]);

			$checkIpUnlike = $this->_commentInfoRepo->all($data['shop_id'], [
				'comment_id' => $data['comment_id'],
				'ip_unlike' => $data['ip'],
				'perPage' => 1,
			]);


			$comment = $this->_commentRepo->detail($data['shop_id'], $data['comment_id']);
			$oldLike = $comment->like;

			if ($checkIpLike->total()) {
				$new_like = intval($oldLike) - 1;
				$type_like = "0";
			} else {
				$new_like = intval($oldLike) + 1;
				$type_like = "1";
			}
			$update = $this->_commentRepo->update($data['shop_id'], $data['comment_id'], ['like' => $new_like]);
			if ($update['status'] == 'success') {
				if($type_like == '1'){
					$this->_commentInfoRepo->insertIpLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}else{
					$this->_commentInfoRepo->deleteIpLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}

				$type_unlike = '0';
				$new_unlike = '';
				if($checkIpUnlike->total()){
					$type_unlike = '-1';
					$oldUnLike = $comment->unlike;
					$new_unlike =intval($oldUnLike) -1;

					$this->_commentRepo->update($data['shop_id'], $data['comment_id'], ['unlike' => $new_unlike]);
					$this->_commentInfoRepo->deleteIpUnLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}

				$result = array(
					'status' => 'success',
					'data' => $new_like,
					'type_like' => $type_like,
					'type_unlike' => $type_unlike,
					'dataUnlike' => $new_unlike,
				);
			}
			$shopInfo = $this->_shopRepo->detail(['shop_id' => $data['shop_id']]);
			if ($shopInfo['status']) {
				$job = new UpdateProductMetafieldJob($data['shop_id'], $shopInfo['shopInfo']->shop_name, $shopInfo['shopInfo']->access_token,$comment->product_id);
				dispatch($job);
			}
		}
		return response()->json($result);
	}

	public function unlike(Request $request) {
		$result = array('status' => 'error', 'message' => "An error, please try again.");
		$data = $request->all();
		$data['ip'] = Helpers::getProxyClientIp();
		if (!empty($data['comment_id']) && !empty($data['shop_id'])) {
			$checkIpLike = $this->_commentInfoRepo->all($data['shop_id'], [
				'comment_id' => $data['comment_id'],
				'ip_like' => $data['ip'],
				'perPage' => 1,
			]);

			$checkIpUnlike = $this->_commentInfoRepo->all($data['shop_id'], [
				'comment_id' => $data['comment_id'],
				'ip_unlike' => $data['ip'],
				'perPage' => 1,
			]);

			$comment = $this->_commentRepo->detail($data['shop_id'], $data['comment_id']);
			$oldUnLike = $comment->unlike;

			if ($checkIpUnlike->total()) {
				$new_unlike = intval($oldUnLike) - 1;
				$type_unlike = "0";
			} else {
				$new_unlike = intval($oldUnLike) + 1;
				$type_unlike = "1";
			}
			$update = $this->_commentRepo->update($data['shop_id'], $data['comment_id'], ['unlike' => $new_unlike]);
			if ($update['status'] == 'success') {
				if($type_unlike == '1'){
					$this->_commentInfoRepo->insertIpUnLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}else{
					$this->_commentInfoRepo->deleteIpUnLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}

				$type_like = '0';
				$new_like = '';
				if($checkIpLike->total()){
					$type_like = '-1';
					$oldLike = $comment->like;
					$new_like =intval($oldLike) -1;

					$this->_commentRepo->update($data['shop_id'], $data['comment_id'], ['like' => $new_like]);
					$this->_commentInfoRepo->deleteIpLike($data['shop_id'], $data['comment_id'], $data['ip']);
				}

				$result = array(
					'status' => 'success',
					'data' => $new_unlike,
					'type_unlike' => $type_unlike,
					'type_like' => $type_like,
					'dataLike' => $new_like,
				);
			}
			$shopInfo = $this->_shopRepo->detail(['shop_id' => $data['shop_id']]);
			if ($shopInfo['status']) {
				$job = new UpdateProductMetafieldJob($data['shop_id'], $shopInfo['shopInfo']->shop_name, $shopInfo['shopInfo']->access_token,$comment->product_id);
				dispatch($job);
			}
		}
		return response()->json($result);
	}

}
