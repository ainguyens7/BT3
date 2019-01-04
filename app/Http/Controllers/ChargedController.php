<?php

namespace App\Http\Controllers;

use App\Events\LogRecordEvent;
use App\Events\SaveIntercomEvent;
use App\Events\UserUpgrade;
use App\Events\UserDowngrade;
use App\Events\PricingChange;
use App\Events\ShopInstall;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\Factory\RepositoryFactory;
use App\Jobs\AddCodeAliReviewsToThemeApps;
use App\Jobs\ImportProductsFromApi;
use App\Jobs\InitDatabaseApps;
use App\Jobs\MetaFieldApps;
use App\Jobs\RemoveAreTrial;
use App\Jobs\ScriptTagApps;
use App\Jobs\WebHookApps;
use App\Events\Logs;
use App\Repository\ShopsRepository;
use App\ShopifyApi\ChargedApi;
use App\ShopifyApi\ShopsApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use DateTime;

/**
 * Class ChargedController
 * @package App\Http\Controllers
 */
class ChargedController extends Controller {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_chargedApi;
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_shopRepo;
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_shopApi;
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $_discountRepo;

	private $_commentRepo;
	

	public function __construct() {
		/**
		 * @var ChargedApi
		 */
		$this->_chargedApi = app( ChargedApi::class );

		/**
		 * @var ShopsRepository
		 */
		$this->_shopRepo = RepositoryFactory::shopsReposity();

		/**
		 * @var ShopsApi
		 */
		$this->_shopApi = app( ShopsApi::class );

		$this->_discountRepo = RepositoryFactory::discountsRepository();
		$this->_commentRepo = RepositoryFactory::commentBackEndRepository();
	}

	/**
	 * Add charge
	 *
	 * @param $app_plan
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function addCharged($app_plan = '', Request $request) {
		if (!empty($app_plan)) {
			// $currentPlan = $request->get('current_plan', '');
			$shopDomain = session('shopDomain');
			$discount = $this->_discountRepo->getCurrentDiscountByUser($shopDomain);

			$this->_chargedApi->getAccessToken(session('accessToken'), $shopDomain);
			$addCharged = $this->_chargedApi->addCharge($app_plan, $discount);

			if (!$addCharged['status']) {
				return view('page_errors.404', ['message' => Lang::get('auth.reinstall_noti')]);
			}

			if (!filter_var($addCharged['addCharge']->confirmation_url,FILTER_VALIDATE_URL)) {
				return view('page_errors.404', ['message' =>'URL charged confirmation not validate']);
			}

			session(['shopInvite' => false, 'plan' => $app_plan]);

			return redirect($addCharged['addCharge']->confirmation_url);
		}

		return view('page_errors.404', ['message' => 'URL charged confirmation not validate']);
	}

	/**
	 * Add charge cho shop với trial day
	 *
	 * @param $trial
	 * @param $app_plan
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function addChargedWithTrial( $app_plan, $trial ) {
		if ( ! empty( $app_plan ) ) {
			$discount = $this->_discountRepo->getCurrentDiscountByUser( session( 'shopDomain' ) );

			$this->_chargedApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );
			$addCharged = $this->_chargedApi->addCharge( $app_plan, $discount, $trial );
			if ( ! $addCharged['status'] ) {
				return view( 'page_errors.404', [ 'message' => Lang::get( 'auth.reinstall_noti' ) ] );
			}

			if ( ! filter_var( $addCharged['addCharge']->confirmation_url, FILTER_VALIDATE_URL ) ) {
				return view( 'page_errors.404', [ 'message' => 'URL charged confirmation not validate' ] );
			}

			session( [
				'shopInvite' => false,            // clear session shop invite
				'plan'       => $app_plan,            // lưu session plan thanh toan
				'trial'      => $trial,            // lưu session trial
			] );

			return redirect( $addCharged['addCharge']->confirmation_url );
		}

		return view( 'page_errors.404', [ 'message' => 'URL charged confirmation not validate' ] );
	}

	public function trialFreeToUnlimited($shop_domain, $app_plan, $trial)
	{
		if ( ! empty( $app_plan ) ) {
			$shop_domain = base64_decode($shop_domain);
			$shopRepo = $this->_shopRepo->detail(['shop_name' => $shop_domain]);
			$discount = $this->_discountRepo->getCurrentDiscountByUser($shop_domain);
			if($shopRepo['status']) {
				$access_token = $shopRepo['shopInfo']->access_token;
			}
			$discount = $this->_discountRepo->getCurrentDiscountByUser( $shop_domain );
			$discount_type = 'percent';

			$data['discount_code']  = 'IMAPRO';
			$checkDiscount = $this->checkDiscount( $data['discount_code'] );
			if ( $checkDiscount['status'] == 'success' ) {
				// cộng discount cho shop nhập mã
				$discount = $discount + $checkDiscount['discount'];

				switch ( $checkDiscount['type'] ) {
					case 'voucher':
						break;
					case 'voucher_amount':
						$discount = $checkDiscount['discount'];
						$discount_type = 'amount';
						break;

					case 'invite':
						$shopInvite = $checkDiscount['shop_invite'];
						session( [
							'shopInvite' => $shopInvite->shop_name, // lưu session mời thành công cho shop mời
						] );
						break;
				}

			}
			$this->_chargedApi->getAccessToken( $access_token, $shop_domain );
			$addCharged = $this->_chargedApi->addCharge( $app_plan, $discount, $trial, $discount_type );
			if ( ! $addCharged['status'] ) {
				return view( 'page_errors.404', [ 'message' => Lang::get( 'auth.reinstall_noti' ) ] );
			}

			if ( ! filter_var( $addCharged['addCharge']->confirmation_url, FILTER_VALIDATE_URL ) ) {
				return view( 'page_errors.404', [ 'message' => 'URL charged confirmation not validate' ] );
			}

			session( [
				'shopInvite' => false,            // clear session shop invite
				'plan'       => $app_plan,            // lưu session plan thanh toan
				'trial'      => $trial,            // lưu session trial
			] );

			return redirect( $addCharged['addCharge']->confirmation_url );
		}

		return view( 'page_errors.404', [ 'message' => 'URL charged confirmation not validate' ] );
	}


	/**
	 * Add charge cho shop với mã giảm giá
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function addDiscountHandle( Request $request ) {
		$data = $request->all();
		if ( ! empty( $data['app_plan'] ) ) {
			$trial_days = 0;
			if (!empty($data['trial_day']) && isset($data['trial_day'])) {
				$trial_days = $data['trial_day'];
			}
			$discount = $this->_discountRepo->getCurrentDiscountByUser( session( 'shopDomain' ) );
			$discount_type = 'percent';


			// kiểm tra mã discount đc mời hoặc voucher của shop
			if ( ! empty( $data['discount_code'] ) ) {
				$checkDiscount = $this->checkDiscount( $data['discount_code'], $request );

				if ( $checkDiscount['status'] == 'success' ) {
					// cộng discount cho shop nhập mã
					$discount = $discount + $checkDiscount['discount'];

					switch ( $checkDiscount['type'] ) {
						case 'voucher':
						break;

						case 'voucher_amount':
							$discount = $checkDiscount['discount'];
							$discount_type = 'amount';
						break;

						case 'invite':
						$shopInvite = $checkDiscount['shop_invite'];
						session( [
								'shopInvite' => $shopInvite->shop_name, // lưu session mời thành công cho shop mời
							] );
						break;
					}

				}
			}

			$this->_chargedApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );
			$addCharged = $this->_chargedApi->addCharge( $data['app_plan'], $discount, $trial_days,$discount_type );
			if ( ! $addCharged['status'] ) {
				return [
					'status'  => 'error',
					'message' => Lang::get( 'auth.reinstall_noti' ),
				];
			}

			if ( ! filter_var( $addCharged['addCharge']->confirmation_url, FILTER_VALIDATE_URL ) ) {
				return [
					'status'  => 'error',
					'message' => 'URL charged confirmation not validate',
				];
			}

			session( [
				'plan' => $data['app_plan'], // lưu session plan thanh toan
			] );

			return [
				'status' => 'success',
				'url'    => $addCharged['addCharge']->confirmation_url,
				'data' => $data
			];
		}

		return [
			'status'  => 'error',
			'message' => 'An error, please try again.',
		];
	}

	/**
	 * Check code discount
	 *
	 * @param $discount_code
	 *
	 * @return array
	 */
	public function checkDiscount( $discount_code, Request $request ) {
		$data = $request->all();
		$shopId = session('shopId');
		// voucher for free user
		$shopInfo = $this->_shopRepo->detail(['shop_id' => $shopId]);

		if ( ! empty( $shopInfo['status'] ) ) {
			$shopInfo = $shopInfo['shopInfo'];
			switch ( strtoupper(trim( $discount_code ))) {
				case 'IMAPRO' :
					// code for free user
				if ( empty( $shopInfo->app_plan ) or $shopInfo->app_plan == 'free' ) {
					return [
						'status'   => 'success',
						'type'     => 'voucher',
						'discount' => 10,
						'message'  => 'Discount 10%',
						'shopInfo' => $shopInfo,
					];
				} else {
					return [
						'status'  => 'error',
						'message' => 'Discount code only for free user.',
					];
				}
				break;

				case 'INFINITY' :
					// code for premium user
					if ( $shopInfo->app_plan == 'premium' ) {
						return [
							'status'   => 'success',
							'type'     => 'voucher',
							'discount' => 10,
							'message'  => 'Discount 10%',
							'shopInfo' => $shopInfo,
						];
					} else {
						return [
							'status'  => 'error',
							'message' => 'Discount code only for pro user.',
						];
					}
					break;

				case 'ALIAFFTERTRIAL':
				if ( ! empty( $shopInfo->trial_date ) and $shopInfo->trial_date != '0000-00-00 00:00:00' ) {
					return [
						'status'   => 'success',
						'type'     => 'voucher',
						'discount' => 15,
						'message'  => 'Discount 15%',
						'shopInfo' => $shopInfo,
					];
				} else {
					return [
						'status'  => 'error',
						'message' => "Sorry. You can't join this promotion.",
					];
				}
				break;
				case 'FAST10':
				$shouldAllowDiscount = $this->shouldAllowDiscountTrialViaEmail($shopInfo);
				if (!$shouldAllowDiscount) {
					return [
						'status'  => 'error',
						'message' => "Sorry. You can't join this promotion.",
					];
				}
				return [
					'status'   => 'success',
					'type'     => 'voucher',
					'discount' => 10,
					'message'  => 'Discount 10%',
					'shopInfo' => $shopInfo,
				];
				break;

				/*case 'FABFCM':
					if ( !empty($data['app_plan']) && $data['app_plan'] == 'unlimited' && $shopInfo->app_plan == 'free' ) {
						return [
							'status'   => 'success',
							'type'     => 'voucher_amount',
							'discount' => 4  ,
							'message'  => 'Discount $4 for UNLIMITED plan',
							'shopInfo' => $shopInfo,
						];
					} else {
						return [
							'status'  => 'error',
							'message' => "Sorry. You can't join this promotion.",
						];
					}
					break;*/
				default :
					// code invite
					/*$shopInvite = $this->_shopRepo->detail(['code_invite' => trim($discount_code)]);
					if($shopInvite['status']){
						if(empty($shopInfo['shopInfo']->app_plan)){
							return [
								'status' => 'error',
								'message' => 'Discount code only use for new user.',
							];
						}

						return [
							'status' => 'success',
							'type' => 'invite',
							'discount' => config('discount.invited'),
							'message' => '-'.config('discount.invited') .'%',
							'shop_invite' => $shopInvite['shopInfo'],
						];
					}*/
				}

				return [
					'status'  => 'error',
					'message' => 'Discount code not match.',
				];
			}

			return [
				'status'  => 'error',
				'message' => 'An error, please try again.',
			];
		}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function chargeHandle(Request $request) {
		// dd($request->all());
		$idCharged = $request->input('charge_id');
		$this->_chargedApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );

		$detailCharged = $this->_chargedApi->detailCharge( $idCharged );
		if ( ! $detailCharged['status'] ) {
			return view( 'page_errors.404', [ 'message' => $detailCharged['message'] ] );
		}
		$detailCharged = $detailCharged['detailCharge'];

		$plan = session( 'plan' );
		$currentPlan = session('current_plan');
		$shopUpgrade = session('shopUpgrade');
		$shopDowngrade = Cache::get('shopDowngrade');

		session([
			'plan' => false,
			'current_plan' => '',
			'shopUpgrade' => false,
			'downgradeToPro' => false
		]);

		Cache::forever('shopDowngrade', false);
		if ( $detailCharged->status == 'accepted' ) {
			$activeCharge = $this->_chargedApi->activeCharge( $idCharged );
			if ( ! $activeCharge['status'] ) {
				return view( 'page_errors.404', [ 'message' => $activeCharge['message'] ] );
			}

			//
			$activeCharge = $activeCharge['activeCharge'];
			//Get Shop info in api
			$this->_shopApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );
			$shopInfo = $this->_shopApi->get();
			//Array record shop save database

			if ( $shopInfo['status'] ) {
				$shopInfo = $shopInfo['shop'];
				$shopInfoInAli = $this->_shopRepo->detail([ 'shop_id' => $shopInfo->id]);

				$recordShop = [
					'shop_id'    => $shopInfo->id,
					//'shop_name' => isset($shopInfo->myshopify_domain) ? $shopInfo->myshopify_domain : null,
					//'shop_email' => isset($shopInfo->email) ? $shopInfo->email : null,
					//'shop_status' => config('common.status.publish'),
					//'shop_country' => isset($shopInfo->country) ? $shopInfo->country : null,
					//'shop_owner' => isset($shopInfo->shop_owner) ? $shopInfo->shop_owner : null,
					//'plan_name' => isset($shopInfo->plan_name) ? $shopInfo->plan_name : null,
					//'app_version' => config('common.app_version', null),
					'billing_id' => isset( $activeCharge->id ) ? $activeCharge->id : null,
					'billing_on' => isset( $activeCharge->billing_on ) ? $activeCharge->billing_on : null,
					//'access_token' => session('accessToken', null),
					'app_plan'   => $plan,
					'updated_at' => date( 'Y-m-d h:i:s', time() ),
				];

				// check trial
				if ( ! empty( session( 'trial' ) ) ) {
					//set job remove are_trial 2 ngày sau khi hết hạn trial mà shop vẫn tiếp tục sử dụng
					$jobRemoveTrial = ( new RemoveAreTrial( $shopInfo->id ) )->delay( Carbon::now()->addDay( config( 'charged' )['trial_days'] + 2 ) );
					$this->dispatch( $jobRemoveTrial );

					session( [
						'trial' => false,                        // xóa session  trial
					] );

					$trial_date               = date( 'Y-m-d H:i:s', time() );
					$recordShop['trial_date'] = $trial_date;
					$recordShop['are_trial']  = 1;
				}

				$saveInfoShop = $this->_shopRepo->insert( $recordShop );
				if ( $saveInfoShop['status'] ) {
					$shopId = $recordShop['shop_id'];
					event( new LogRecordEvent($shopId, $plan));
					if ($shopUpgrade && !empty($currentPlan)) {
						event(new UserUpgrade($shopId, $currentPlan, $plan));
						event( new LogRecordEvent( session( 'shopId' ), 'upgrade' ) );
						if($plan == config('plans.premium.name')){
							event(new SaveIntercomEvent($shopId,'upgraded-pro'));
						}else{
							event(new SaveIntercomEvent($shopId,'upgraded-unlimited'));
						}
					}

					if ($shopDowngrade) {
						event(new UserDowngrade($shopId, 'unlimited', 'premium'));
						event(new SaveIntercomEvent($shopId,'downgraded-pro'));
					}
					//check lượt thanh toán này có phải được mời hay k
					/*if(!empty(session('shopInvite'))){
						// Lưu discount lại để nó dùng những lần charge sau
						$saveDiscount =  $this->_discountRepo->save([
							'shop_name' => session('shopDomain'),
							'discount' => config('discount.invited'),
							'type' => 'invited',
							'shop_related' => session('shopInvite'),
							'status' => 1,
						]);

						// lưu lần mời thành công cho shop
						$saveInvite =  $this->_discountRepo->save([
							'shop_name' =>  session('shopInvite'),
							'discount' => 0,
							'type' => 'invite',
							'shop_related' =>session('shopDomain'),
							'status' => 1,
						]);

						session([
							'shopInvite' => false,	                    // xóa session  shopInvite
						]);
					}*/
					// add credit charge for shop re-install;
					$shopReInstallWithMaybeRefund = Cache::get("{$shopId}_reInstallWithMaybeRefund");
					Cache::forget("{$shopId}_reInstallWithMaybeRefund");
					//check if maybe refund and old plan = new plan -> add credit (refund)
					if (!empty($shopReInstallWithMaybeRefund) && $shopReInstallWithMaybeRefund == $shopId) {
						if($shopInfoInAli['status']){
							$shopInfoInAli = $shopInfoInAli['shopInfo'];
							if($shopInfoInAli->app_plan == $plan){
								$creditCharge = $this->_chargedApi->addCreditCharge($plan);
								if ($creditCharge['status']) {
									event(new LogRecordEvent($shopId, 'add-credit-charge', $creditCharge['creditCharge']->id));
								} else {
									event(new LogRecordEvent($shopId, 'add-credit-charge', false));
								}

							}
						}
					}
					$url = route( 'apps.charge_successful' );
					event(new PricingChange(session('shopId'), session('shopDomain'), session('accessToken')));

					return redirect( $url );
				}


				return view( 'page_errors.404', [ 'message' => $shopInfo['message'] ] );
			}

			return view( 'page_errors.404', [ 'message' => $shopInfo['message'] ] );

		} else {
			return view( 'chargedApp.decline', compact( 'plan' ) );
		}
	}

	public function chargeThank() {
		return view( 'chargedApp.thank' );
	}

	public function chargeSuccessful()
	{
		$shopId = session('shopId');
		$shopDomain = session('shopDomain');
		$accessToken = session('accessToken');
		$userType = Cache::get($shopId.'_user_type');
		if ($userType === 'new') {
			event(new ShopInstall($shopId, $shopDomain, $accessToken));
		}
		Cache::forever($shopId.'_user_type', '');
		return view('chargedApp.thank')->with(['non_google_analytics' => true]);
	}

	private function getDayUserJoined($shopInfo)
	{
		return $shopInfo->getDayUserJoined();
	}

	private function shouldAllowDiscountTrialViaEmail($shopInfo)
	{
		$dayUserJoined = $this->getDayUserJoined($shopInfo);

		$dayCompare = $dayUserJoined > 7;

		if ($dayCompare && $shopInfo->app_plan === 'free') {
			return $this->checkDiscount24h();
		}
		return false;
	}
	
	private function shouldAllowDiscount($shopInfo)
	{
		$dayUserJoined = $this->getDayUserJoined($shopInfo);

		$configDefaultDayShow = config('marketing.defaultDayDisplayPopupForFree');

		$dayCompare = $dayUserJoined > $configDefaultDayShow;

		if ($dayCompare && $shopInfo->app_plan === 'free') {
			return $this->checkFlasDealAvaiableFor24H();
		}
		return false;
	}

	private function checkDiscount24h()
	{
		$shopId = session('shopId');
		$keyFlashDealExpire = "{$shopId}_trial_via_email";
		$flashDealExpire = session($keyFlashDealExpire);
		$now = new DateTime('now');
		$expireInTime = new DateTime($flashDealExpire);
		$diff = $now->diff($expireInTime);
		if ($diff->invert) {
			return false;
		}
		return true;
	}

	private function checkFlasDealAvaiableFor24H()
	{
		$shopId = session('shopId');
		$keyFlashDealExpire = "{$shopId}_flashDealExpireOn";
		$flashDealExpire = session($keyFlashDealExpire);
		$now = new DateTime('now');
		$expireInTime = new DateTime($flashDealExpire);
		$diff = $now->diff($expireInTime);
		if ($diff->invert) {
			return false;
		}
		return true;
	}
}
