<?php

namespace App\Listeners;

use App\Facades\WorkerApi;
use Illuminate\Support\Facades\URL;
use App\Factory\RepositoryFactory;

class ResetSettingDefault
{
    protected $shop;

    protected $shopMeta;

    public function __construct(RepositoryFactory $factory)
    {
        $this->shop = $factory::shopsReposity();
        $this->shopMeta = $factory::shopsMetaReposity();
    }
    /**
     * Inject asset
     */
    public function resetSettingDefault($event)
    {
//        $shopId = $event->shopId;
//        $rawShopPlan = $this->shop->shopPlansInfo($shopId);
//        if (!$rawShopPlan['status']) {
//            return;
//        }
//
//        $shopPlanInfo = $rawShopPlan['planInfo'];
//        $data['setting'] = array_merge(config('settings.setting'), [
//            'approve_review' => config('settings.approve_review'),
//            'active_frontend' => config('settings.active_frontend')
//        ]);
//        $this->shopMeta->updateGeneralSettings($shopId, $data);
    }


    public function resetThemeDefault($event)
    {
//        $shopId = $event->shopId;
//        $data = [];
//        $data['rating_point']    = config('settings.rating_point');
//        $data['rating_card']     = config('settings.rating_card');
//        $data['style']           = config('settings.style');
//        $data['shop_id']         = $shopId;
//		$data['style_customize'] = config('settings.style_customize');
//        $data['style_customize'] = false;
//        $data['setting']         = array_merge(config('settings.setting'),
//                                        $this->createArrayObject(array(
//                                            'get_only_star',
//                                            'get_only_picture',
//                                            'get_only_content',
//                                            'translate_reviews',
//                                            'get_max_number_review',
//                                            'country_get_review',
//                                            'except_keyword',
//                                            'affiliate_program',
//                                            'affiliate_aliexpress',
//                                            'affiliate_admitad')
//                                        )
//                                    );
//
//        $this->shopMeta->updateThemeSettings($shopId, $data);
    }

    public function resetTranslateDefault($event)
    {
//        $shopId = $event->shopId;
//        $data['translate'] = config('settings.translate');
//        $this->shopMeta->update($shopId, $data);
    }

    /**
     * Register multi listeners
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\ResetSettingDefault@resetSettingDefault'
        );
        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\ResetSettingDefault@resetThemeDefault'
        );
        $events->listen(
            'App\Events\UpdateApp',
            'App\Listeners\ResetSettingDefault@resetTranslateDefault'
        );
    }

    private function createArrayObject($arrayParam = array()){
        $shop_id                 = session( 'shopId' );
        $detailShopMetaRepo      = $this->shopMeta->detail($shop_id);
        $data  = [] ;
        if($detailShopMetaRepo){
            $objectDetailSetting     = json_decode($detailShopMetaRepo->setting,true) ;
            foreach ($arrayParam as $item){
                $data[$item]  =  !empty($objectDetailSetting[$item]) ? $objectDetailSetting[$item] :config('settings.'.$item.'');
            }
        }
        return $data ;
    }
}