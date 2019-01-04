<?php

namespace App\Http\Controllers;

use App\Events\SaveIntercomEvent;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Events\ChangeSetting;
use App\Events\UpdateCache;
use App\Http\Requests\SettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use App\Events\SaveLocalTranslate;

class SettingsController extends Controller {
	/**
	 * @var
	 */
	private $_shopRepo;
	private $_shopMetaRepo;

	public function __construct( RepositoryFactory $factory ) {
		$this->_shopRepo     = $factory::shopsReposity();
		$this->_shopMetaRepo = $factory::shopsMetaReposity();
	}

	/**
	 * Page general settings
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function settings() {
		$shop_id = session( 'shopId' );
		$shop_name = '';
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		$shopInfo = $this->_shopRepo->detail(['shop_id' => $shop_id]);
		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		
		if ($shopInfo['status']) {
			$shopInfo = $shopInfo['shopInfo'];
			$shop_name = $shopInfo->shop_name;
		}

		$allCountryCode = Helpers::getCountryCode();

		$affiliateProgram = 'none';
		$affiliateProAliexpress = '';
		$affilaiteProAdmitad = '';

		$settings = Helpers::getSettings($shop_id);

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
		}

		if ($shopPlanInfo['name'] == 'unlimited') {
			$affiliateProgram = isset($settings['setting']['affiliate_program']) ? $settings['setting']['affiliate_program'] : 'none';
			$affilaiteProAdmitad = $affiliateProgram == 'admitad' ? $settings['setting']['affiliate_admitad'] : '';
			$affiliateProAliexpress = $affiliateProgram == 'aliexpress' ? $settings['setting']['affiliate_aliexpress'] : '';
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

            if(!empty($settings['setting']) && is_array($settings['setting'])  && !array_key_exists('male_name_percent',$settings['setting'])){
                $settings['setting']['male_name_percent'] = config('settings.setting.male_name_percent');
            }
            if(!empty($settings['setting']) && is_array($settings['setting']) &&  !array_key_exists('female_name_percent',$settings['setting'])){
                $settings['setting']['female_name_percent'] = config('settings.setting.female_name_percent');
        }

		if(empty($settings['setting']['approve_review_stars']) or !is_array($settings['setting']['approve_review_stars'])){
			$settings['setting']['approve_review_stars'] = config('settings.setting.approve_review_stars');
		}
    

		return view( 'settings.settings', compact(
			'allCountryCode',
			'settings',
			'shopPlanInfo',
			'affiliateProgram',
			'affilaiteProAdmitad',
			'affiliateProAliexpress',
			'shop_name',
			'pictureOption',
			'contentOption'
		));
	}

	/**
	 * Save general settings
	 *
	 * @param SettingsRequest $request
	 *
	 * @return mixed
	 */
	public function settingsHandle( SettingsRequest $request ) {
		$data = $request->all();

		$shop_id         = session( 'shopId' );

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		$data['shop_id'] = $shop_id;
		/*$shopPlanInfo = $this->_shopRepo->shopPlansInfo(session('shopId'));
        if(!empty($shopPlanInfo['status']))
            $shopPlanInfo = $shopPlanInfo['planInfo'];*/
		if(!isset($data['approve_review'])){
			$data['approve_review'] = 0;
		}

		if(!isset($data['active_frontend'])){
			$data['active_frontend'] = 0;
		}
		if ($shopPlanInfo['name'] == 'unlimited') {
			if (!isset($data['setting']['affiliate_program'])) {
				$data['setting']['affiliate_program'] = 'none';
				unset($data['setting']['affiliate_aliexpress']);
				unset($data['setting']['affiliate_admitad']);						
			} else if ($data['setting']['affiliate_program'] == "aliexpress") {
				$data['setting']['affiliate_program'] = 'aliexpress';
				$data['setting']['affiliate_aliexpress']['api_key'] = isset($data['setting']['affiliate_aliexpress']['api_key']) ? $data['setting']['affiliate_aliexpress']['api_key'] : '';
				$data['setting']['affiliate_aliexpress']['tracking_id'] = isset($data['setting']['affiliate_aliexpress']['tracking_id']) ? $data['setting']['affiliate_aliexpress']['tracking_id'] : '';
				unset($data['setting']['affiliate_admitad']);
			} else if ($data['setting']['affiliate_program'] == "admitad") {
				$data['setting']['affiliate_program'] = 'admitad';
				$data['setting']['affiliate_admitad']['affiliate_id'] = isset($data['setting']['affiliate_admitad']['affiliate_id']) ? $data['setting']['affiliate_admitad']['affiliate_id'] : '';
				unset($data['setting']['affiliate_aliexpress']);
			} else {
				$data['setting']['affiliate_program'] = 'none';
				unset($data['setting']['affiliate_aliexpress']);
				unset($data['setting']['affiliate_admitad']);
			}
		}
		$data['setting'] =  array_merge($data['setting'], $this->createArrayObject(array('section_show', 'sort_reviews', 'display_rate_list', 'date_format', 'active_date_format','max_number_per_page','display_advance_sort')));
		//if(!isset($data['setting']['sort_reviews'])) $data['setting']['sort_reviews'] = 'sort_by_date';
		//$sort_reviews = $data['setting']['sort_reviews'];
		/*if(!empty($shopPlanInfo['sort_reviews']) && !in_array($sort_reviews,$shopPlanInfo['sort_reviews']))
		{
			return Redirect::route( 'settings.view' )->with( 'error', 'An error, please try again.' );
		}*/
		/*$settings = $this->_shopMetaRepo->detail( $shop_id );
		if ( empty( $settings ) ) {
			$rs = $this->_shopMetaRepo->create( $data );
		} else {
			$rs = $this->_shopMetaRepo->update( $shop_id, $data );
		}*/

		if(!empty($data['setting']['except_keyword'])){
			event(new SaveIntercomEvent($shop_id,'added-keyword'));
		}

		$rs = $this->_shopMetaRepo->updateGeneralSettings( $shop_id, $data );

		if ( $rs['status'] ) {
			event(new ChangeSetting($shop_id, session('accessToken'), session('shopDomain')));
			return Redirect::route( 'settings.view' )->with( 'success', $rs['message'] );
		}

		return Redirect::route( 'settings.view' )->with( 'error', $rs['message'] );
	}

	/**
	 * Page setting translate
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function localTranslate(){
		$settings = Helpers::getSettings(session('shopId'));
		if (isset($settings['translate']['label_image']) && $settings['translate']['label_image'] === 'Image') {
			$settings['translate']['label_image'] = config('settings.translate.label_image');
		}
		return view('settings.localTranslate',compact('settings'));
	}

	/**
	 * Save translate settings
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function localTranslateHandle(Request $request){
		$data = $request->except('_token');
		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;
		$data['translate']['optional']  = "";
		$rs = $this->_shopMetaRepo->update( $shop_id, $data );
		if ( $rs['status'] ) {
			event(new SaveLocalTranslate(session('shopId'), session('shopDomain'), session('accessToken')));
			event(new UpdateCache(session('shopId')));
			return Redirect::route( 'settings.localTranslate' )->with( 'success', $rs['message'] );
		}

		return Redirect::route( 'settings.localTranslate' )->with( 'error', $rs['message'] );
	}

	/**
	 * Ajax reset local translate
	 *
	 * @param Request $request
	 */
	public function resetLocalTranslate(Request $request){
		$result = array(
			'status' => 'error',
			'message' => 'An error, please try again.'
		);

		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;
		$data['translate'] = config('settings.translate');

		$rs = $this->_shopMetaRepo->update( $shop_id,$data);

		if ( $rs['status'] ) {
			event(new UpdateCache(session('shopId')));
			$result = array(
				'status' => 'success',
				'message' => 'Reset local translate successful.'
			);
		}

		return response()->json($result);
	}

	/**
	 * Ajax change turn on/off translate
	 *
	 * @param Request $request
	 */
	public function statusLocalTranslate(Request $request){
		$data = $request->all();
		$result = array(
			'status' => 'error',
			'message' => 'An error, please try again.'
		);

		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;

		$rs = $this->_shopMetaRepo->update( $shop_id,$data);

		if ( $rs['status'] ) {
			$result = array(
				'status' => 'success',
				'message' => 'Save setting successful.'
			);
		}
		return response()->json($result);
	}

	/**
	 * Theme  setting page
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function themesSetting(){
		$shop_id = session( 'shopId' );
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);

		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];

		$settings = Helpers::getSettings($shop_id);
        if ((int)$settings['style'] <= 2 ){
            $settings['style']  = 2 ;
        } else {
            $settings['style']  = 5;
		}
		return view( 'settings.themesSettings', compact(  'settings','shopPlanInfo' ) );
	}

	/**
	 * Save theme settings
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function themesSettingHandle(Request $request){

		$data = $request->all();
        $shop_id         = session( 'shopId' );

		$data['shop_id'] = $shop_id;

		if(!isset($data['is_code_css'])){
			$data['is_code_css'] = 0;
		}

        if(!isset($data['setting']['section_show'])){
            $data['setting']['section_show'] = '';
        }

        if(!isset($data['setting']['display_rate_list'])){
            $data['setting']['display_rate_list'] = 0;
		}
		
        if(!isset($data['setting']['active_date_format'])){
            $data['setting']['active_date_format'] = 0;
		}
		
		if(!isset($data['setting']['display_advance_sort'])){
            $data['setting']['display_advance_sort'] = 0;
		}
		
		$detailShopMetaRepo  = $this->_shopMetaRepo->detail($shop_id) ;
		//check setting not empty will merge two setting
		if($detailShopMetaRepo){
            if(!empty($detailShopMetaRepo->setting && !empty($data['setting']))){
                $data['setting'] = array_merge(json_decode($detailShopMetaRepo->setting ,true), $data['setting']);
            }
        }

        if(empty($data['setting'])){
            unset($data['setting']) ;
        }

        // check plan info
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		// save update themes setting
		$rs = $this->_shopMetaRepo->updateThemeSettings( $shop_id, $data );
		if ( $rs['status'] ) {
			return Redirect::route( 'settings.themesSetting' )->with( 'success', $rs['message'] );
		}

		return Redirect::route( 'settings.themesSetting' )->with( 'error', $rs['message'] );
	}

	/**
	 * ajax reset theme setting
	 */
	public function resetThemesSettings(){
		$result = array(
			'status' => 'error',
			'message' => 'An error, please try again.'
		);
		$shop_id                 = session( 'shopId' );
        $data['rating_point']    = config('settings.rating_point');
        $data['rating_card']     = config('settings.rating_card');
        $data['style']           = config('settings.style');
        $data['shop_id']         = $shop_id;
		$data['style_customize'] = config('settings.style_customize');
        $data['style_customize'] = false;
        $data['is_code_css']     = 0;
        $data['code_css']        = config('settings.code_css');
        $data['setting']         = array_merge(config('settings.setting'),
                                        $this->createArrayObject(array(
                                            'get_only_star',
                                            'get_only_picture',
                                            'get_only_content',
                                            'translate_reviews',
                                            'get_max_number_review',
                                            'country_get_review',
                                            'except_keyword',
                                            'affiliate_program',
                                            'affiliate_aliexpress',
                                            'affiliate_admitad')
                                        )
                                    );
        $rs = $this->_shopMetaRepo->updateThemeSettings( $shop_id,$data);

		if ( $rs['status'] ) {
			$result = array(
				'status' => 'success',
				'message' => 'Reset theme settings successful.'
			);
		}

		echo json_encode($result); exit();
	}

    /**
     * @param array $arrayParam
     * @return mixed
     */
    private function createArrayObject($arrayParam = array()){
        $shop_id                 = session( 'shopId' );
        $detailShopMetaRepo      = $this->_shopMetaRepo->detail($shop_id);
        $data  = [] ;
        if($detailShopMetaRepo){
            $objectDetailSetting     = json_decode($detailShopMetaRepo->setting,true) ;
            foreach ($arrayParam as $item){
                $data[$item]  =  !empty($objectDetailSetting[$item]) ? $objectDetailSetting[$item] :config('settings.'.$item.'');
            }
        }
        return $data ;
    }
	public function themesStore(){
		$shop_id = session( 'shopId' );
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];

		$settings = Helpers::getSettings($shop_id);

		return view('settings.themesStore',compact('settings','shopPlanInfo'));
	}

	public function themesStoreHandle(Request $request){
		$data = $request->all();

		$shop_id         = session( 'shopId' );
		$data['shop_id'] = $shop_id;

		$shopPlanInfo = $this->_shopRepo->shopPlansInfo($shop_id);
		if($shopPlanInfo['status'])
			$shopPlanInfo = $shopPlanInfo['planInfo'];

		if(empty($shopPlanInfo['rating_point']) or !in_array($data['rating_point'],$shopPlanInfo['rating_point'])){
			return Redirect::route( 'settings.themesStore' )->with( 'error', 'An error, please try again' );
		}

		if(empty($shopPlanInfo['rating_card']) or !in_array($data['rating_card'],$shopPlanInfo['rating_card'])){
			return Redirect::route( 'settings.themesStore' )->with( 'error', 'An error, please try again' );
		}

		$rs = $this->_shopMetaRepo->update( $shop_id, $data );

		if ( $rs['status'] ) {
			return Redirect::route( 'settings.themesStore' )->with( 'success', $rs['message'] );
		}

		return Redirect::route( 'settings.themesStore' )->with( 'error', $rs['message'] );
	}
}
