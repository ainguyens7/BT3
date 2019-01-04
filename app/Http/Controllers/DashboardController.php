<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Services\WidgetReviewService;

class DashboardController extends Controller
{
	protected $_commentRepo;
	protected $_productRepo;
	protected $_shopRepo;
	protected $_discountRepo;
	protected $_shopMetaRepo;

	public function __construct()
	{
		$this->_commentRepo = RepositoryFactory::commentBackEndRepository();
		$this->_productRepo = RepositoryFactory::productsRepository();
		$this->_shopRepo = RepositoryFactory::shopsReposity();
		$this->_discountRepo = RepositoryFactory::discountsRepository();
		$this->_shopMetaRepo = RepositoryFactory::shopsMetaReposity();
	}

	public function index(Request $request)
	{
		$dashboardPage = 'dashboard-page';

		$this_month = date('Y').'-'.date('m').'-01';

		$reviewWaiting = 0;
		$productsReviews = 0;
		$reviewImport = 0;
		$reviewImportThisMonth = 0;

		// get current session shop id
		$sessionShopId = session('shopId');
		$widgetService = new WidgetReviewService();

		$reviewBadge = htmlentities("<div product-id=\"{{ product.id }}\" class=\"alr-display-review-badge\"></div>");
		$reviewBadgeCollection = htmlentities("<div product-id=\"{{ product.id }}\" class=\"arv-collection arv-collection--{{ product.id }}\"></div>");

		$reviewBox = htmlentities("
<div id=\"shopify-ali-review\" product-id=\"{{ product.id }}\"> 
	{{ shop.metafields.review_collector.review_code }} 
</div>");

		$reviewBox_2 = htmlentities("
<div id=\"shopify-ali-review\" product-id=\"{{ product.id }}\">
	<div class=\"shop_info\" shop-id=\"$sessionShopId\" shop-name=\"{{ shop.permanent_domain }}\">
		<div class=\"reviews\"></div>
	</div>
</div>");

		// get list reviews are pending
		$listReviewWaiting = $this->_commentRepo->all($sessionShopId, 
		[
			'status' => 'pending',
			'source' => 'web',
		]);

		$reviewWaiting = !empty($listReviewWaiting->total()) ? $listReviewWaiting->total() : 0;

		// get list reviews was imported
		$listReviewsImport =  $this->_commentRepo->all($sessionShopId,
		[
			'source' => ['aliexpress', 'oberlo','aliorder']
		]);

		// set reviewImport equal to total list reviews imported
		$reviewImport = !empty($listReviewsImport->total()) ? $listReviewsImport->total() : 0;

		// get list reviews was imported this month
		$listReviewsImportThisMonth =  $this->_commentRepo->all($sessionShopId,
		[
			'source' => ['aliexpress', 'oberlo' ,'aliorder'],
			'from' => $this_month,
		]);

		// set reviewImportThisMonth equal to total list reviews was imported this month
		$reviewImportThisMonth = !empty($listReviewsImportThisMonth->total()) ? $listReviewsImport->total() : 0;
//		$filter['is_review'] = 1;
//		$listProductsReviews = $this->_productRepo->getAll($sessionShopId, $filter);
//		$shouldSetProductReview = !empty($listProductsReviews['status']) && !empty($listProductsReviews['products']->total());
		$productsReviews = $this->_productRepo->countReviewProduct($sessionShopId);

		$shopInfo = $this->_shopRepo->detail(['shop_id' => $sessionShopId]);

		if(!empty($shopInfo['status'])){
			$shopInfo = $shopInfo['shopInfo'];
			if (!empty($shopInfo->code_invite)) {
				$code_invite = $shopInfo->code_invite;
			} else {
				$code_invite = (md5($shopInfo->shop_domain.time()));
				$this->_shopRepo->update(['shop_id'=> $sessionShopId],['code_invite' => $code_invite]);
			}
		}
		$sessionShopDomain = session('shopDomain');
		$listInvited = $this->_discountRepo->getDiscountsByUser($sessionShopDomain, ['invite']);
		$total_invited = $this->_discountRepo->countInviteOfUser($sessionShopDomain);

		$show_onboarding = false;

		if (empty(session('showedPolicy'))) {
			$show_onboarding = true;
			session(['showedPolicy' => true]);
		}

		return view('dashboard.dashboard', compact(
			'dashboardPage',
			'code_invite',
			'reviewImport',
			'reviewImportThisMonth',
			'productsReviews',
			'reviewWaiting',
			'total_invited',
			'listInvited',
			'show_onboarding',
			'reviewBadge',
			'reviewBox',
			'reviewBox_2',
			'reviewBadgeCollection'
		));
	}

	/**
	 *  Check should show event flash deal marketing
	 */
	private function shouldShowEventFlashDeal()
	{
		return config('marketing.shouldEnableEventFlashDeal');
	}

	private function getDayUserJoined($shopInfo)
	{
		return $shopInfo->getDayUserJoined();
	}

	private function shouldShowEventForFreeUser($shopInfo)
	{
		return $shopInfo->app_plan === 'free';
	}

	private function setSessionAvaiableForFreeUser()
	{
		$now = new DateTime('now');
		// Expire after 24h;
		$dateInterval = DateInterval::createFromDateString(config('marketing.flashDealAvaiableFor'));
		$flashDealExpire = $now->add($dateInterval)->format('Y-m-d H:i:s');

		// generate key
		$shopId = session('shopId');
		$key = "{$shopId}_flashDealExpireOn";
		// set key store in session
		session([$key => $flashDealExpire]);
	}

	/**
	 * Check should show event for free user
	 */
	private function shouldShowEventFlashDealForFreeUser($shopInfo)
	{
		$shouldShowEvent = $this->shouldShowEventFlashDeal();

		if (!$shouldShowEvent) {
			return false;
		}
		$forFreeUser = $this->shouldShowEventForFreeUser($shopInfo);
		if (!$forFreeUser) {
			return $forFreeUser;
		}

		$this->setSessionAvaiableForFreeUser($shopInfo);

		$dayUserJoined = $this->getDayUserJoined($shopInfo);

		$configDefaultDayShow = config('marketing.defaultDayDisplayPopupForFree');

		$dayCompare = $dayUserJoined > $configDefaultDayShow;
		return $dayCompare;
	}

	public function checkVariableEmail5()
	{
		$shopId = session('shopId');
		$showPopup5 = false;
		$shopRepo = $this->_shopRepo->detail(['shop_id' => $shopId]);
		$createdDate = $shopRepo['shopInfo']->created_at;
		$currentDate = Carbon::now();
		$diffDate = $currentDate->diffInDays($createdDate);
		$shopMetaRepo = $this->_shopMetaRepo->detail($shopId);
		$pageReview =  empty($shopMetaRepo->page_reviews)? null: $shopMetaRepo->page_reviews;
		$numberShowPopup5 = Redis::get($shopId.'_numberShowPopup5');
		if($pageReview == null && $diffDate >= 7 && empty($numberShowPopup5)) {
			$showPopup5 = true;
		}
		echo "createdDate: ".$createdDate."<br>".
			 "currentDate: ".$currentDate."<br>".
			 "diffDate: ".$diffDate."<br>".
			 "pageReview: ".$pageReview."<br>".
			 "numberShowPopup5: ".$numberShowPopup5."<br>".
			 "showPopup5: ".$showPopup5;
	}

	public function checkVariablePopup6()
	{
		$shopId = session('shopId');
		$showPopup6 = false;
		$shopRepo = $this->_shopRepo->detail(['shop_id' => $shopId]);
		$createdDate = $shopRepo['shopInfo']->created_at;
		$currentDate = Carbon::now();
		$diffDate = $currentDate->diffInDays($createdDate);
		$useThemesSetting = Redis::get($shopId.'_themesSetting');
		$numberShowPopup6 = Redis::get($shopId.'_numberShowPopup6');
		if(empty($useThemesSetting) && $diffDate >= 10 && empty($numberShowPopup6)) {
			$showPopup6 = true;
		}
		echo "createdDate: ".$createdDate."<br>".
			 "currentDate: ".$currentDate."<br>".
			 "diffDate: ".$diffDate."<br>".
			 "useThemesSetting: ".$useThemesSetting."<br>".
			 "numberShowPopup6: ".$numberShowPopup6."<br>".
			 "showPopup6: ".$showPopup6;
	}

	public function checkVariableEmail7()
	{
		$sendEmail7 = false;
		$shopId = session('shopId');
		$shopRepo = $this->_shopRepo->detail(['shop_id' => $shopId]);
		$appPlan = $shopRepo['shopInfo']->app_plan;
		$createdDate = $shopRepo['shopInfo']->created_at;
		$currentDate = Carbon::now();
		$diffDate = $currentDate->diffInDays($createdDate);
		$numberShowPopup3 = Redis::get($shopId.'_numberShowPopup3');
		$numberShowPopup4 = Redis::get($shopId.'_numberShowPopup4');
		$numberShowPopup5 = Redis::get($shopId.'_numberShowPopup5');
		$numberShowPopup6 = Redis::get($shopId.'_numberShowPopup6');
		for($i=0; $i<1; $i++) {
			if($appPlan == "free" && $diffDate >= 14 && !empty($numberShowPopup3) && !empty($numberShowPopup4)
				&& !empty($numberShowPopup5)) {
				$sendEmail7 = true;
				break;
			}
			if($appPlan == "free" && $diffDate >= 14 && !empty($numberShowPopup3) && !empty($numberShowPopup4)
				&& !empty($numberShowPopup6)) {
				$sendEmail7 = true;
				break;
			}
		}
		echo "appPlan: ".$appPlan."<br>".
			 "createdDate: ".$createdDate."<br>".
			 "currentDate: ".$currentDate."<br>".
			 "diffDate: ".$diffDate."<br>".
			 "numberShowPopup3: ".$numberShowPopup3."<br>".
			 "numberShowPopup4: ".$numberShowPopup4."<br>".
			 "numberShowPopup5: ".$numberShowPopup5."<br>".
			 "numberShowPopup6: ".$numberShowPopup6."<br>".
			 "sendEmail7: ".$sendEmail7;

	}
}
