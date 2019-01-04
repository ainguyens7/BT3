<?php
/**
 * Created by PhpStorm.
 * User: dev-08
 * Date: 9/5/2018
 * Time: 1:14 PM
 */

namespace App\Http\Controllers;


use App\Events\ChangedShopifyThemeEvent;

use App\Events\ShopInstall;
use App\Events\UpdateProductCS;
use App\Events\UpdateSettingAppCS;
use App\Factory\RepositoryFactory;
use App\Jobs\AddCodeAliReviewsToThemeApps;
use App\Jobs\MetaFieldApps;
use App\ShopifyApi\ThemesApi;
use Illuminate\Http\Request;


class CSController extends Controller
{
    private $_themesApi ;

    private $_shopRepo ;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->_shopRepo   = $repositoryFactory::shopsReposity();
        $this->_themesApi  = new ThemesApi();
        $this->sentry = app('sentry');
    }

    public function updateThemeCS(){
        $themeApi = app(ThemesApi::class);
        $themeApi->getAccessToken( session('accessToken'), session('shopDomain') );
        $listThemeName  =  $themeApi->getAllTheme();
        $themeName      = '' ;
        $themeId = null;
        foreach ( $listThemeName['allTheme'] as $key=>$value) {
            if ($value->role === 'main') {
                $themeName  = $value->name;
                $themeId  = $value->id;
                break ;
            }
        }
        return view('cs.cs-update-themes', compact('themeName' , 'themeId')) ;
    }

    public function handleUpdateThemeCS(Request $request){
        try{
            $shopRepo   = RepositoryFactory::shopsReposity();
            $shopName   = $this->_shopRepo->detail(array('shop_id'=>session('shopId')));
            $objectShop = $shopRepo->detail(array('shop_name' => $shopName['shopInfo']['shop_name']));
            if ($objectShop['status']) {
                $shopInfo = $objectShop['shopInfo'];
                event(new ChangedShopifyThemeEvent(
                    $shopInfo->shop_id,
                    $shopInfo->shop_name,
                    $shopInfo->access_token
                ));
                return response()->json(['status'=>true, 'messages'=>__('cs.cs_update_themes_success')]);
            }

        }catch (\Exception $exception){
            return $this->sentry->captureException($exception);
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateSettingAppCS(){

        return view('cs.cs-update-settings',[]) ;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handleUpdateSettingAppCS(Request $request){
        try{
            $shopRepo   = RepositoryFactory::shopsReposity();
            $shopName   = $this->_shopRepo->detail(array('shop_id'=>session('shopId')));
            $objectShop = $shopRepo->detail(array('shop_name' => $shopName['shopInfo']['shop_name']));
            if ($objectShop['status']) {
                $shopInfo = $objectShop['shopInfo'];

                event(new UpdateSettingAppCS(
                    $shopInfo->shop_id,
                    $shopInfo->shop_name,
                    $shopInfo->access_token
                ));
                return response()->json(['status'=>true, 'messages'=>__('cs.cs_update_settings_success')]);
            }

        }catch (\Exception $exception){
            return $this->sentry->captureException($exception);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateProductsCS(){

        return view('cs.cs-update-products',[]) ;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handleUpdateProductsCS(Request $request){
        try{

            $shopRepo   = RepositoryFactory::shopsReposity();
            $shopName   = $this->_shopRepo->detail(array('shop_id'=>session('shopId')));
            $objectShop = $shopRepo->detail(array('shop_name' => $shopName['shopInfo']['shop_name']));
            if ($objectShop['status']) {
                $shopInfo = $objectShop['shopInfo'];
                event(new UpdateProductCS(
                    $shopInfo->shop_id,
                    $shopInfo->shop_name,
                    $shopInfo->access_token
                ));

                sleep(3);
                return response()->json(['status'=>true, 'messages'=>__('cs.cs_update_products_success')]);
            }

        }catch (\Exception $exception){
            return $this->sentry->captureException($exception);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateMasterCS(){

        return view('cs.cs-update-master',[]) ;
    }

    /**
     * @return mixed
     */
    public function handleUpdateMasterCS()
    {
        try {
            $shopId         = session('shopId');
            $accessToken    = session('accessToken');
            $shopDomain     = session('shopDomain');
            (event(new ShopInstall($shopId, $shopDomain, $accessToken)));
            dispatch(new AddCodeAliReviewsToThemeApps($accessToken, $shopDomain));
            dispatch(new MetaFieldApps($accessToken, $shopDomain, $shopId));
            return response()->json(['status'=>true, 'messages'=>__('cs.cs_update_master_success')]);
        } catch (\Exception $exception) {
            return $this->sentry->captureException($exception);
        }
    }
}