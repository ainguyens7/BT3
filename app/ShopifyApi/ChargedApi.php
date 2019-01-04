<?php

namespace App\ShopifyApi;


use App\Services\GuzzleHttp;
use App\Factory\RepositoryFactory;
use Exception;

class ChargedApi extends GuzzleHttp
{
	protected $sentry;
	public function __construct()
	{
		$this->sentry = app('sentry');
	}
	private $_shopTest = [
		'shopdunz.myshopify.com',
		'thelastsamurai.myshopify.com',
		'alireview.myshopify.com',
		'alireviews.myshopify.com',
		'quanstore1.myshopify.com',
		'duylinh-store.myshopify.com',
		'vosydao88.myshopify.com',
		'travel-for-life.myshopify.com',
		'fashion-life-people.myshopify.com',
		'bcd2.myshopify.com',
		'bcd3.myshopify.com',
		'bcd4.myshopify.com',
		'alireview-live.myshopify.com',
		'secretappreciation.myshopify.com',
		'fireappsmkt.myshopify.com',
		'yenhn.myshopify.com',
		'anhle4.myshopify.com',
		'anhle3.myshopify.com',
		'anhle2.myshopify.com',
		'vsd-08.myshopify.com',
		'doof-store.myshopify.com',
		'ali-reviews-fireapps.myshopify.com',
		'garangion.myshopify.com',
		'garchienbo.myshopify.com',
		'lorem-dev.myshopify.com',
		'anhledev.myshopify.com',
		'thongpv.myshopify.com',
		'anhlelive1.myshopify.com',
		'anhlelive2.myshopify.com',
		'anhlelive3.myshopify.com',
		'anhlelive4.myshopify.com',
		'anhlelive5.myshopify.com'
	];

	/*private $_shopOneCharge = [
		'crazy-future-fashion.myshopify.com'
	];*/

	/*private $_listShopDiscount =[
		'thingsvital.myshopify.com',
		'haute-for-tots.myshopify.com',
		'gym-beast-mode.myshopify.com',
		'panthershop.shopify.com',
		'marcandspade.myshopify.com',
		'need-it-nation.myshopify.com',
		'frozen-lake.myshopify.com',
		'bestsellrz.myshopify.com',
		'kinetic-gadgets.myshopify.com',
		'allroundshop.myshopify.com',
		'the-babyfirst.myshopify.com',
		'fashion-eaziloot.myshopify.com',
		'pop-the-k.myshopify.com',
		'online-treasure-shop.myshopify.com',
		'animeshopstore.myshopify.com',
		'shopgonews.myshopify.com',
		'supergadget-store.myshopify.com',
		'liveonearth.myshopify.com',
		'dealkdo.myshopify.com',
		'justbb-net.myshopify.com',
		'liveonearth.myshopify.com',
		'winter-gitz.myshopify.com',
		'online-variety-mall.myshopify.com',
		'the-womens-stores.myshopify.com',
		'nz-boutique.myshopify.com',
		'ghrian-sportswear.myshopify.com',
		'happypugs.myshopify.com',
	];*/

	public function addCharge($app_plan,$discount = 0,$trial = 0,$discount_type = 'percent')
	{
		$plan_info = config('plans.'.$app_plan);

		try{
				$name = config('charged.name');
				if (!empty($plan_info)) {
					$name = $plan_info['name'] === config('plans.unlimited.name') ? 'Unlock all awesome features with Unlimited' : 'Unlimited products & reviews with Pro';
				}
			$data = [
				'recurring_application_charge' => [
					'name' => $name,
					'price' => !empty($plan_info) ? $plan_info['price'] : config('charged.price'),
					'return_url' => route('apps.chargeHandle'),
					'test' => config('charged.test'),
					// 'trial_days' => config('charged.default_trial_days')
				]
			];


			$shopRepo = RepositoryFactory::shopsReposity();
			$shopId = session('shopId');
			$rawShop = $shopRepo->detail(['shop_id' => $shopId]);
			if ($rawShop['status']) {
				$shopInfo = $rawShop['shopInfo'];
				$shopifyPlanName = !is_null($shopInfo->plan_name) ? $shopInfo->plan_name : '';
				if ($shopifyPlanName === config('charged.app_friendly')) {
					$data['recurring_application_charge']['test'] = true;
				}
			}
			if(!empty($trial))
				$data['recurring_application_charge']['trial_days'] = $trial;

			if(in_array(session('shopDomain'), $this->_shopTest))
				$data['recurring_application_charge']['test'] = true;

			if(!empty($discount) and is_numeric($discount)) {
				if($discount_type == 'amount'){
					$discountPrice = $discount;
				}else{
					$discountPrice = number_format($plan_info['price'] * ($discount / 100), 2, '.', '.');
				}
				$data['recurring_application_charge']['price'] = $plan_info['price'] - $discountPrice;
			}
			$addCharge = $this->post('recurring_application_charges.json', $data);

			return ['status' => true, 'addCharge' => $addCharge->recurring_application_charge];
		} catch (\Exception $exception)
		{
			$this->sentry->captureException($exception);
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

	public function addCreditCharge($app_plan)
	{
		$plan_info = config('plans.'.$app_plan);
		$shopDomain = session('shopDomain');
		try {
			$data = [
				'application_credit' => [
					'description' => "application credit for refund {$shopDomain}",
					'amount' => !empty($plan_info) ? $plan_info['price'] : config('charged.price')
				]
			];
			$shopRepo = RepositoryFactory::shopsReposity();
			$shopId = session('shopId');
			$rawShop = $shopRepo->detail(['shop_id' => $shopId]);
			if ($rawShop['status']) {
				$shopInfo = $rawShop['shopInfo'];
				$shopifyPlanName = !is_null($shopInfo->plan_name) ? $shopInfo->plan_name : '';
				if ($shopifyPlanName === config('charged.app_friendly')) {
					$data['application_credit']['test'] = true;
				}
			}

			$creditCharge = $this->post('application_credits.json', $data);

			return ['status' => true, 'creditCharge' => $creditCharge->application_credit];

		} catch (Exception $exception) {
			$this->sentry->captureException($exception);
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

	public function detailCharge($id)
	{
		try{
			$detailCharge = $this->get(
				'recurring_application_charges/'.$id.'.json'
			);
			return ['status' => true, 'detailCharge' => $detailCharge->recurring_application_charge];

		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

	public function activeCharge($idCharge)
	{
		try{
			$activeCharge = $this->post(
				'recurring_application_charges/'.$idCharge.'/activate.json'
			);
			return ['status' => true, 'activeCharge' => $activeCharge->recurring_application_charge];
		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

	public function allCharge()
	{
		try{
			$allCharge = $this->get(
				'recurring_application_charges.json'
			);
			return ['status' => true, 'allCharge' => $allCharge->recurring_application_charges];
		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}

	public function deleteCharge($idCharge){
		try{
			$deleteCharge = $this->drop(
				'recurring_application_charges/'.$idCharge.'.json'
			);
			return ['status' => true, 'activeCharge' => $deleteCharge->recurring_application_charge];
		} catch (\Exception $exception)
		{
			return ['status' => false, 'message' => $exception->getMessage()];
		}
	}
}
