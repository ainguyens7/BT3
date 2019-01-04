<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\ImportProductsFromApi;
use App\Jobs\InitDatabaseApps;
use App\Jobs\MetaFieldApps;
use App\Jobs\ScriptTagApps;
use App\Jobs\WebHookApps;
use App\Models\CommentsModel;
use App\Models\ProductsModel;
use App\Repository\ProductsRepository;
use App\Repository\ShopMetaRepository;
use App\Repository\LogRepository;
use App\Services\GuzzleHttp;
use App\Services\ShopifyServices;
use App\ShopifyApi\CustomeCollectionsApi;
use App\ShopifyApi\MetaFieldApi;
use App\ShopifyApi\ProductsApi;
use App\ShopifyApi\ScriptTagApi;
use App\ShopifyApi\WebHookApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use DateTime;
use DateInterval;

class ProductsController extends Controller
{
    private $_shopMetaRepo;

    private $_productRepo;


    private $_commentBackendRepo;

    private $_shopRepo;

    protected $_logRepo;

    public function __construct()
    {
        $this->_shopMetaRepo = RepositoryFactory::shopsMetaReposity();

        $this->_productRepo = RepositoryFactory::productsRepository();

        $this->_commentBackendRepo = RepositoryFactory::commentBackEndRepository();

        $this->_shopRepo = RepositoryFactory::shopsReposity();

        $this->_logRepo = RepositoryFactory::logRepository();

    }

    public function listProduct(Request $request)
    {
        $shopId = session('shopId');
	    $filter = $request->all();
        $checkPopup = $this->shouldShowPopupRemovePowerBy($shopId);
	    //Get list products
        $listProduct = $this->_productRepo->getAll($shopId, $filter);
        //Count products
        $countProduct = $this->_productRepo->countProduct($shopId, $filter);
        $countReviews = $this->_commentBackendRepo->countTotalReviewImported($shopId,'all');

        $shopInfo = $this->_shopRepo->detail(['shop_id' => $shopId]);
        $settings = Helpers::getSettings($shopId);

        $pictureOption = [];
		$contentOption = [];
        if (!empty($settings['setting'])) {
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
        }

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

        $shopMeta = json_encode($settings['setting']);

        $allCountry = json_encode(Helpers::getCountryCode(), true);

        $show_onboarding = !empty(session('show_onboarding')) ? session('show_onboarding') : false;
	    session( ['show_onboarding' => false ] );

	    if($listProduct['status'] && $shopInfo['status'])
        {
            $listProduct = $listProduct['products'];
            $shopInfo = $shopInfo['shopInfo'];
            $totalReview = 0;
            foreach ($listProduct as &$v) {
                $v->total_reviews = $this->_commentBackendRepo->getTotalReview($v->id,$shopId);
                $v->avg_reviews = $this->_commentBackendRepo->getAvgStar($v->id, $shopId);
                $totalReview += $this->_commentBackendRepo->getTotalReview($v->id,$shopId);
            }

//            $listProductsImportedReviews = $this->_productRepo->getListProductsImportedReviews($shopId,false);
//            $listProductsImportedReviewsPublish = $this->_productRepo->getListProductsImportedReviews($shopId);

            return view('products.list_product', compact('showPopup2', 'showPopup3','showPopup4', 'shopAppPlan' ,
                'listProduct', 'shopInfo', 'countProduct','filter', 'shopMeta', 'allCountry','show_onboarding', 'shopId',
                'countrySetting','countReviews'));
        }

       return view('page_errors.404', ['message' => $listProduct['message']]);

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

    private function generatePopup($avaiableShowPopup)
    {
        // return NULL if not avaiable
        if (!$avaiableShowPopup) return NULL;
        $staticPopup = config('marketing.popup');
        $result = NULL;
        if (session()->has('popupEventMarketing')) {
            $avaiablPopup = session('popupEventMarketing');
            $date = date('Ymd');
            $i = 0;
            // it will stop if result not null
            while ($i < count($avaiablPopup) && $result == NULL){
                if ($date >= $avaiablPopup[$i]['start_date'] && $date < $avaiablPopup[$i]['end_date']) {
                    $result = $staticPopup[$avaiablPopup[$i]['popup_key']];
                }
                $i++;
            }
            return $result;
        }
        $defaultJumpDayShow      = config('marketing.jumpDay');
        $popupEventMarketing    = $this->generateInternalPopup();
        session([ 'popupEventMarketing' => $popupEventMarketing ]);
        return $staticPopup[0];
    }

    private function generateInternalPopup($count = 3 , $jumpDay = 3) {
        $arr = [];
        $numberDate = $jumpDay;
        for ($i = 0; $i < $count ; $i++) {
            // start of day
            $startDate = mktime(0, 0, 0, date("m") ,date("d") + $jumpDay - $numberDate, date("Y"));
            // end of day
            $endDate = mktime(0, 0, 0, date("m") ,date("d") + $jumpDay, date("Y"));

            $arr[$i] = [
                    'popup_key'  => $i,
                    'start_date' => date('Ymd' ,$startDate),
                    'end_date'   => date('Ymd' ,$endDate),
                ];
            $jumpDay += 3;
        }
        return $arr;
    }
}
