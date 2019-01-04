<?php

namespace App\Http\Controllers;

use App\Events\LogRecordEvent;
use App\Events\SaveIntercomEvent;
use App\Events\UserDowngrade;
use App\Events\ShopReinstall;
use App\Events\Logs;
use App\Events\ShopInstall;
use Illuminate\Support\Facades\Cache;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Http\Requests\InstallAppsRequest;
use App\Jobs\AddCodeAliReviewsToThemeApps;
use App\Jobs\RemoveReviewBox;
use App\Jobs\RemoveReviewStar;
use App\Jobs\ImportProductsFromApi;
use App\Jobs\InitDatabaseApps;
use App\Jobs\MetaFieldApps;
use App\Jobs\ScriptTagApps;
use App\Jobs\SendMailInstall;
use App\Jobs\UpdateDatabase;
use App\Jobs\WebHookApps;
use App\Services\ShopifyServices;
use App\ShopifyApi\ChargedApi;
use App\ShopifyApi\ShopsApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use DateInterval;
use DateTime;
use App\Events\UpdateApp;
use App\Events\PricingChange;
use Illuminate\Support\Facades\Validator;

class AppsController extends Controller {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_shopifyService;

	private $_shopRepo;

	private $_shopApi;

	private $_chargedApi;

	private $_commentRepo;

	private $_commentDefaultRepo;

	private $_shopMetaRepo;

	private $_productRepo;

	private $_discountRepo;

	protected $sentry;

	/**
	 * AppsController constructor.
	 *
	 * @param RepositoryFactory $factory
	 */
	function __construct(ChargedApi $charged_api, ShopifyServices $shopify_services, ShopsApi $shops_api, RepositoryFactory $factory) {
		$this->_shopifyService = $shopify_services;
		/**
		 * @var $this ->_shopRepo ShopsRepository
		 */
		$this->_shopRepo = $factory->shopsReposity();
		/**
		 * @var $this ->_shopApi ShopsApi
		 */
		$this->_shopApi = $shops_api;

		/**
		 * @var $this ->_charedApi ChargedApi
		 */
		$this->_chargedApi = $charged_api;

		$this->_commentRepo = $factory::commentBackEndRepository();

		$this->_commentDefaultRepo = $factory::commentsDefaultRepository();

		$this->_shopMetaRepo = $factory::shopsMetaReposity();

		$this->_productRepo = $factory::productsRepository();

		$this->_discountRepo = $factory::discountsRepository();
		$this->sentry = app('sentry');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function installApp() {
		session( [ 'accessToken' => '', 'shopDomain' => '', 'shopId' => '' ] );

		return view( 'apps.install_app' );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function installAppHandle(Request $request ) {
		$validator = Validator::make($request->all(), [
			'shop' => 'required|shopify_domain',
		]);

		if ($validator->fails()) {
			$message = Lang::get('validation.shopify_domain');
			return view( 'apps.install_app',compact('message') );
		}else{
			$urlStore = $request->input( 'shop', null );

			//check new stall or old install
			$this->_shopifyService->setShopDomain( $urlStore );
			$installUrl = $this->_shopifyService->installURL();

			return redirect( $installUrl );
		}
	}

	/**
	 * @param Request $request
	 *
	 * @return bool|string
	 */
	public function auth(Request $request) {
		try {
		$request = $request->all();
		$auth = $this->_shopifyService->authApp($request);
		if ($auth['status']) {
			$shopDomain  = $request['shop'];
			$accessToken = $auth['accessToken'];
			//Save session accessToken and shopDomain
			$this->_shopApi->getAccessToken($accessToken, $shopDomain);
			$shopInfoApi = $this->_shopApi->get();
			if (!$shopInfoApi['status']) {
				return redirect(route('apps.installApp'))->with('error', $shopInfoApi['message']);
			}

			$shopInfoApi = $shopInfoApi['shop'];

			$app_plan  = '';
			$user_type = 'new';

			$shopInfoDB = $this->_shopRepo->detail(['shop_id' => $shopInfoApi->id]);
			if ($shopInfoDB['status']) {
				$app_plan  = $shopInfoDB['shopInfo']->app_plan;
				$user_type = "old";
				
				//check neu da cai roi thi tra ve trang chu
				if ($shopInfoDB['shopInfo']->shop_status == 1) {
					session([
						'accessToken' => $accessToken,
						'shopDomain'  => $shopDomain,
						'shopId'      => $shopInfoApi->id
					]);

					// store access token
					$shopInfoDB['shopInfo']->access_token = $accessToken;
					$shopInfoDB['shopInfo']->save();

					return redirect(route('apps.dashboard'));
				}
			}

			$recordShop   = [
				'shop_id'      => $shopInfoApi->id,
				'shop_name'    => isset($shopInfoApi->myshopify_domain) ? $shopInfoApi->myshopify_domain : null,
				'shop_email'   => isset($shopInfoApi->email) ? $shopInfoApi->email : null,
				'shop_status'  => config('common.status.publish'),
				'shop_country' => isset($shopInfoApi->country) ? $shopInfoApi->country : null,
				'shop_owner'   => isset($shopInfoApi->shop_owner) ? $shopInfoApi->shop_owner : null,
				'plan_name'    => isset($shopInfoApi->plan_name) ? $shopInfoApi->plan_name : null,
				'app_version'  => config('common.app_version', null),
				'app_plan'     => $app_plan,
				'access_token' => $accessToken
			];
			$saveInfoShop = $this->_shopRepo->insert($recordShop);

			if ($saveInfoShop['status']) {
				// event(new LogRecordEvent($recordShop['shop_id'], 'install'));
				// if ($app_plan === config('plans.free.name')) {
				// 	event(new LogRecordEvent($recordShop['shop_id'], $app_plan));
				// }

				session( [
					'accessToken' => $accessToken,
					'shopDomain'  => $shopDomain,
					'shopId'      => $shopInfoApi->id,
					'is_install'  => true
				] );
				if ($user_type == 'new') {
					Cache::forever($shopInfoApi->id.'_user_type', 'new');
					return redirect(route('apps.choicePlan'));
				} else {
					if ($app_plan == 'free') {
						return redirect(route('apps.charge_successful'));
					}
					// shop re-install

					// check if shop re-install in 30day cycle charge -> add flag re-installer maybe refund
					$lastBillingId = Cache::get("{$shopInfoApi->id}_lastBillingId");
					if(!empty($lastBillingId)){
						Cache::forget("{$shopInfoApi->id}_lastBillingId");
						$this->_chargedApi->getAccessToken($accessToken, $shopDomain);
						$lastBilling = $this->_chargedApi->detailCharge($lastBillingId);
						if($lastBilling['status'] && $lastBilling['detailCharge']->status == 'cancelled'){
							$endCycleOn = date('Y-m-d',strtotime('+30 days',strtotime($lastBilling['detailCharge']->activated_on)));

							if(date('Y-m-d') <  $endCycleOn){
								Cache::forever("{$shopInfoApi->id}_reInstallWithMaybeRefund", $shopInfoApi->id);
							}
//							Cache::forever("{$shopInfoApi->id}_shouldCreateApplicationCreditCharge", true);
						}
					}

					event(new ShopReinstall($shopInfoApi->id, $shopDomain, $accessToken));
					return redirect(route('apps.confirmUpgrade'));
				}
			}

			return redirect(route('apps.installApp'))->with('error', $saveInfoShop['message']);
		}

		return redirect(route('apps.installApp'))->with('error', $auth['message']);
		} catch (Exception $ex) {
			$this->sentry->captureException($ex);
			return redirect(route('apps.installApp'))->with('error', $ex->getMessage());
		}
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getStart() {
		return view( 'apps.get_started' );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function updateApp() {

		return view( 'apps.update_app' );
	}

	public function criticalUpdateApp()
	{
		return view( 'apps.critical_update_app' );
	}

	public function criticalUpdateAppHandle()
	{
		$shopId = session('shopId');
		$accessToken = session('accessToken');
		$shopDomain = session('shopDomain');
		(event(new ShopInstall($shopId, $shopDomain, $accessToken)));
		dispatch(new AddCodeAliReviewsToThemeApps($accessToken, $shopDomain));
		dispatch(new MetaFieldApps($accessToken, $shopDomain, $shopId));
		$result = array(
			'status'  => 'success',
			'message' => 'Update app successful.',
			'url'     => route( 'apps.dashboard' ),
		);
		return response()->json($result);
	}

	/**
	 *
	 */
	public function updateAppHandle() {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'settings.failed' ),
		);

		$shop = $this->_shopRepo->detail( [ 'shop_name' => session( 'shopDomain' ) ] );
		if ( $shop['status'] ) {
			$shop_info = $shop['shopInfo'];
			event(new UpdateApp(session('shopId'), session('shopDomain'), session('accessToken')));
			$update = $this->_shopRepo->update( [ 'shop_id' => session( 'shopId' ) ], [ 'app_version' => config( 'common.app_version' ) ] );
			if ( $update ) {
				$result = array(
					'status'  => 'success',
					'message' => 'Update app successful.',
					'url'     => route( 'apps.dashboard' ),
				);
			}

		}
		return response()->json($result);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function updateThemes() {

		return view( 'apps.update_themes' );
	}

	/**
	 *
	 *Xử lý shop thay theme
	 *
	 */
	public function updateThemesHandle() {
		$result = array(
			'status'  => 'error',
			'message' => Lang::get( 'settings.failed' ),
		);

		$shop = $this->_shopRepo->detail( [ 'shop_name' => session( 'shopDomain' ) ] );

		if ( $shop['status'] ) {

			/**
			 *
			 */
			dispatch( new AddCodeAliReviewsToThemeApps( session( 'accessToken' ), session( 'shopDomain' ), session( 'shopId' ) ) );

			dispatch( new ScriptTagApps( session( 'accessToken' ), session( 'shopDomain' ) ) );

			$result = array(
				'status'  => 'success',
				'message' => 'Update themes successful.',
				'url'     => route( 'apps.getStart' ),
			);

		}

		if ( $result['status'] == 'success' ) {
			sleep( 7 );
		}
		echo json_encode( $result );
		exit();
	}

	/**
	 * @param Request $request
	 *
	 * @return bool
	 */
	public function reviewApp( Request $request ) {
		return $this->_shopRepo->update( [ 'shop_id' => session( 'shopId' ) ], [ 'is_review_app' => config( 'common.is_reviews_app.reviews' ) ] );
	}

	/**
	 * Trang chọn lại gói
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function upgrade() {
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo( session( 'shopId' ) );

		return view( 'apps.plans.upgrade', compact( 'shopPlanInfo' ) );
	}

	/**
	 * Xử lý nâng cấp gói
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function upgradeHandle(Request $request) {
		$shopId = session('shopId');
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shopId);
		$data = $request->all();

		$currentPlan = '';
		if ( $shopPlanInfo['status'] ) {
			$currentPlan = $shopPlanInfo['planInfo']['name'];
		}
		if (!empty($data['plan'])) {
			if (empty($currentPlan) && $data['plan'] == config('plans')['free']['name']) {
				$update = $this->_shopRepo->update(['shop_id' => $shopId], [ 'app_plan' => $data['plan']]);
				if ($update) {
					// event(new PricingChange(session('shopId'), session('shopDomain'), session('accessToken')));
					return redirect(route('apps.charge_successful'));
				}
			}
			session(['shopUpgrade' => true, 'current_plan' => $currentPlan]);
			return redirect(route('apps.addCharged', ['app_plan' => $data['plan']]));
		} else {
			return redirect(route('404'));
		}
	}

	/*
	 * Trang xác nhận gói khi cài lại app
	 */
	public function confirmUpgrade(Request $request)
	{
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo(session('shopId'));
		return view('apps.plans.confirmUpgrade', compact('shopPlanInfo'));
	}

	/**
	 * Xóa toàn bộ data cũ để downgrade
	 *
	 * @param Request $request
	 */
	public function removeDataHandle(Request $request)
	{
		$result = array(
			'status'  => trans('api.status', ['status' => 'error']),
			'message' => trans('api.error'),
		);

		if (!$request->exists('plan')) {
			$result['message'] = trans('api.bad_request_parameter');
			return response()->json($result, 400);
		}

		$plan = $request->input('plan');
		$allowPlan = ['free', 'premium'];

		if (!in_array($plan, $allowPlan)) {
			$result['message'] = trans('api.bad_request_parameter');
			return response()->json($result, 400);
		}

		$shopId = session('shopId');

		$shopRaw = $this->_shopRepo->detail(['shop_id' => $shopId]);
		if (!$shopRaw['status']) {
			return response()->json($shopRaw, 500);
		}

		$shopInfo = $shopRaw['shopInfo'];

		/**
		 * Handle downgrade
		 * If app plan is free: immediately update shop and clear current charge in table shop and shopify services
		 * If app plan not free: create new charge, store variable to know this 
		 * going to downgrade, when user approve charge and
		 * active charge successfully. We will update shop
		 * and update current charge table shop
		 */

		if ($plan === 'free') {
			// update shop and clear charge
			// update shop
			$chargeID = $shopInfo->billing_id;
			$currentPlan = $shopInfo->app_plan;

			$updating = [
				'app_plan'   => $plan,
				'billing_id' => '',
				'billing_on' => ''
			];

			$updateShop = $this->_shopRepo->update(
				['shop_id' => $shopId],
				$updating
			);

			if (!$updateShop) {
				return response()->json($updateShop, 500);
			}
			event(new LogRecordEvent($shopId, 'downgrade'));
			event(new LogRecordEvent($shopId, $plan));
			event(new UserDowngrade($shopId, $currentPlan, $plan));
			event(new SaveIntercomEvent($shopId,'downgraded-free'));

			// update product, i dont know why run update product haha
            // $updateProduct = $this->_productRepo->updateProductByShop($shopId);
			// $updateMeta = $this->_shopMetaRepo->update($shopId, config('settings'));
			// remove current charge
			$accessToken = session('accessToken');
			$this->_chargedApi->getAccessToken($accessToken);
			$this->_chargedApi->deleteCharge($chargeID);
			$url = route('apps.charge_successful');
			$result['status'] = trans('api.status', ['status' => 'success']);
			$result['message'] = trans('api.downgrade_success');
			$result['url'] = $url;
			event(new PricingChange(session('shopId'), session('shopDomain'), session('accessToken')));
			return response()->json($result, 200);
		}

		// handle update when plan not free
		$keyShopDowngradeUseEvent = 'shopDowngrade';
		Cache::forever($keyShopDowngradeUseEvent, true);

		$url = route('apps.addCharged', ['app_plan' => $plan]);

		$result['status'] = trans('api.status', ['status' => 'success']);
		$result['message'] = '';
		$result['url'] = $url;
		return response()->json($result, 200);
	}

	/**
	 * Trang chọn gói khi cài lần đầu tiên
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function choicePlan( Request $request ) {

		return view( 'apps.plans.choicePlan' );
	}

	/**
	 * Trang thông báo cài mới thành công
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function successInstall( Request $request ) {
		if ( ! empty( session( 'is_install' ) ) ) {
			$jobSendMail = ( new SendMailInstall( session( 'shopId' ) ) );
			$this->dispatch( $jobSendMail );
			session( [ 'is_install' => false, 'show_onboarding' => true ] );
		}

		return view( 'apps.successInstall' );
	}


	/**
	 * Ajax get info of plan
	 */
	public function planInfo( Request $request ) {
		$data = $request->all();
		if ( ! empty( $data['plan'] ) ) {
			$planInfo = config( 'plans' )[ $data['plan'] ];

			if ( ! empty( $planInfo ) ) {
				$discount = $this->_discountRepo->getCurrentDiscountByUser( session( 'shopDomain' ) );
				$result   = [
					'name'             => ( $planInfo['name'] ),
					'package_name'     => strtoupper( $planInfo['title'] ),
					'price'            => $planInfo['price'],
					'total_payment'    => $planInfo['price'] - $planInfo['price'] * $discount / 100,
					'current_discount' => $discount,
				];

				echo json_encode( [
					'status' => 'success',
					'data'   => $result,
				] );
				exit();
			}
		}

		echo json_encode( [
			'status'  => 'error',
			'message' => 'An error, please try again.',
		] );
		exit();
	}


	public function emailUnSubscribe() {

		return view( 'apps.emailUnSubscribe' );
	}

	public function trialDay()
	{
		$shopId = session('shopId');
		$shopInfo = $this->_shopRepo->detail(['shop_id' => $shopId]);
		$checkFree = $this->shouldShowPopupRemovePowerBy($shopId);
		$trial_day = 0;
		if($shopInfo['status'])
		{
			$shopInfo = $shopInfo['shopInfo'];
			$dayUserJoined = $shopInfo->getDayUserJoined();
			$shouldSetSession = $dayUserJoined >= 7 && $checkFree;
			if ($shouldSetSession) {
				$trial_day = 7;
				$this->setSessionAvaiableForFreeUser();
			}
		}
		return view( 'apps.plans.trialDay' ,compact('trial_day'));
	}

	private function shouldShowPopupRemovePowerBy($shopId = '')
	{
		$gateFree = config('plans.free.name');
		if (empty($shopId)) {
			return false;
		}
		$shopInfo = $this->_shopRepo->shopPlansInfo($shopId);

		if (!isset($shopInfo['status'])) {
			return false;
		}

		if ($shopInfo['status'] === true && isset($shopInfo['planInfo'])) {
			$planInfo = $shopInfo['planInfo'];
			return $planInfo['name'] === $gateFree;
		}
		return false;
	}

	private function setSessionAvaiableForFreeUser()
	{
		$now = new DateTime('now');
		// Expire after 24h;
		$dateInterval = DateInterval::createFromDateString(config('marketing.flashDealAvaiableFor'));
		$flashDealExpire = $now->add($dateInterval)->format('Y-m-d H:i:s');

		// generate key
		$shopId = session('shopId');
		$key = "{$shopId}_trial_via_email";
		// set key store in session
		session([$key => $flashDealExpire]);
	}

	public function trialDayHandle( Request $request ) {
		$data = $request->all();

		$shopInfo = $this->_shopRepo->detail( [ 'shop_id' => session( 'shopId' ) ] );
		if ( $shopInfo['status'] ) {
			$shopInfo = $shopInfo['shopInfo'];
			if ( $shopInfo->app_plan == 'free' ) {
				$plan_trial = $data['plan'];

				//check shop đã từng trial hay chưa
				if ( empty( $shopInfo->trial_date ) or $shopInfo->trial_date == '0000-00-00 00:00:00' ) {
					return redirect( route( 'apps.addChargedWithTrial', [
						'app_plan' => $plan_trial,
						'trial'    => config( 'charged' )['trial_days']
					] ) );
				}
			}

			return view( 'page_errors.404', [ 'message' => 'You do not enough request to join this promotion!!' ] );
		}


		return view( 'page_errors.404', [ 'message' => 'An error, please try again.' ] );
	}
}
