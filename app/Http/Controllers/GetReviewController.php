<?php

namespace App\Http\Controllers;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Repository\CommentBackEndRepository;
use App\Repository\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Helper\Helper;

class GetReviewController extends Controller
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_commentBackendRepo;
    private $_shopRepo;

    private $_productRepo;

    /**
     * GetReviewController constructor.
     */
    public function __construct()
    {
        $this->_commentBackendRepo = RepositoryFactory::commentBackEndRepository();
        $this->_productRepo = RepositoryFactory::productsRepository();
        $this->_shopRepo = RepositoryFactory::shopsReposity();
    }

    // public function checkShowPopup2($shopId) {
    //     $checkPopup2_1 = Redis::get($shopId.'_numberShowPopup2_1');
    //     $checkPopup2_2 = Redis::get($shopId.'_numberShowPopup2_2');
    //     $shopAppPlan = $this->_logRepo->checkAppPlan($shopId);
    //     $numberProductReview = $this->_productRepo->countReviewProduct($shopId);
    //     $oldPlan = $this->_logRepo->checkLog($shopId);
    //     $checkPopup4 = Redis::get($shopId.'_numberShowPopup4');
    //     $checkSendEmail7 = Redis::get($shopId.'_numberSendEmail7');
    //     $showPopup2 = false;
    //     if($shopAppPlan == 'premium' && empty($checkPopup4) && empty($checkSendEmail7)) {
    //         if($oldPlan == 'upgrade') {
    //             if($numberProductReview == 15 && empty($checkPopup2_1)) {
    //                 Redis::set($shopId.'_numberShowPopup2_1', 1);
    //                 $showPopup2 = true;
    //             }
    //             if($numberProductReview == 20 && empty($checkPopup2_2)) {
    //                 Redis::set($shopId.'_numberShowPopup2_2', 1);
    //                 $showPopup2 = true;
    //             }
    //         }
    //         else {
    //             if($numberProductReview == 5 && empty($checkPopup2_1)) {
    //                 Redis::set($shopId.'_numberShowPopup2_1', 1);
    //                 $showPopup2 = true;
    //             }
    //             if($numberProductReview == 10 && empty($checkPopup2_2)) {
    //                 Redis::set($shopId.'_numberShowPopup2_2', 1);
    //                 $showPopup2 = true;
    //             }
    //         }
    //     }
    //     return $showPopup2;
    // }

//    public function checkShowPopup3($shopId) {
//        $newUser = $this->_logRepo->checkFreeUser($shopId);
//        $numberProductReview = $this->_productRepo->countReviewProduct($shopId);
//        $numberShowPopup3 = Redis::get($shopId.'_numberShowPopup3');
//        if ($newUser && $numberProductReview == 3 && empty($numberShowPopup3)) {
//            Redis::set($shopId.'_numberShowPopup3', 1);
//            return true;
//        }
//        return false;
//    }

    // public function checkShowPopup4($shopId) {
    //     $showPopup4 = false;
    //     $newUser = $this->_logRepo->checkFreeUser($shopId);
    //     $numberProductReview = $this->_productRepo->countReviewProduct($shopId);
    //     $numberShowPopup4 = Redis::get($shopId.'_numberShowPopup4');
    //     if($newUser && $numberProductReview == 10 && empty($numberShowPopup4)) {
    //         $showPopup4 = true;
    //         Redis::set($shopId.'_numberShowPopup4', 1);
    //     }
    //     return $showPopup4;
    // }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveReviewAliexpress(Request $request)
    {
        $data = $request->all();
        $showPopup2 = false;
        $showPopup4 = false;
        // we dont need check has aliexpress_link parameter, default will empty
        $review_link = $request->input('review_link', '');

        if(empty($data['reviewObj'])) {
            return response()->json(['status' => false, 'message' => 'Cannot reviews obj']);
        }

        $data['reviewObj'] = json_decode($data['reviewObj'], true);

        if ($data['shop_id'] != session('shopId')) {
            return response()->json(['status' => false, 'message' => 'ShopId Invalid']);
        }

        if (count($data['reviewObj']) < 1) {
            return response()->json(['status' => false, 'message' => 'Product not review']);
        }


        if (isset($data['get_max_number_review'])) {
            $data['reviewObj'] = array_slice($data['reviewObj'], 0, $data['get_max_number_review']);
        }

        $shopPlanInfo = $this->_shopRepo->shopPlansInfo($data['shop_id']);
        
	    if (!empty($shopPlanInfo['status'])) {
		    $shopPlanInfo = $shopPlanInfo['planInfo'];
        }
        $shouldSaveRevieObj = $this->_commentBackendRepo->saveObjReviewAliexpress(
            $data['shop_id'],
            $data['product_id'],
            $data['reviewObj'],
            $data['type'],
            $shopPlanInfo,
            'aliexpress'
        );

        if ($shouldSaveRevieObj) {
            //Update product table
            $this->_productRepo->update($data['shop_id'], $data['product_id'], ['is_reviews' => config('common.is_reviews.reviews'), 'review_link' => $review_link]);
            
            $avgReviews = Helpers::getAvgStarInObjectRating($data['reviewObj']);
            $totalReviews = count($data['reviewObj']);

            // $numberShowPopup2_1 = Redis::get($data['shop_id'].'_numberShowPopup2_1');
            // $numberShowPopup2_2 = Redis::get($data['shop_id'].'_numberShowPopup2_2');
            // $numberShowPopup4 = Redis::get($data['shop_id'].'_numberShowPopup4');
            // for($i=0;$i<1;$i++) {
            //     // check pop up 2
            //     if(empty($numberShowPopup2_1)) {
            //         $showPopup2 = $this->checkShowPopup2($data['shop_id']);
            //         if($showPopup2){
            //             break;
            //         }
            //     }
            //     else if(empty($numberShowPopup2_2)) {
            //         $showPopup2 = $this->checkShowPopup2($data['shop_id']);
            //         if($showPopup2){
            //             break;
            //         }
            //     }
            //     // check pop up 4
            //     if(empty($numberShowPopup4)) {
            //         $showPopup4 = $this->checkShowPopup4($data['shop_id']);
            //         if($showPopup4){
            //             break;
            //         }
            //     }
            // }

            return response()->json(['status' => true, 'totalReviews' => $totalReviews, 'avgReviews' => $avgReviews]);
        }

        return response()->json(['status' => false, 'message' => 'Cannot save review to database']);
    }

    public function checkAddReview(Request $request) 
    {
        $result = [];
        $review_link = '';
	    $is_add_review = 'error'; // hết số lượng product được add
        $data = $request->all();
        $has_imported_review = false;
    	if(!empty($data['product_id'])) {
		    $shopPlanInfo = $this->_shopRepo->shopPlansInfo(session('shopId'));
		    if(!empty($shopPlanInfo['status'])) {
			    $shopPlanInfo = $shopPlanInfo['planInfo'];
            }
		    $product = $this->_productRepo->detail(session('shopId'), $data['product_id']);
            $review_link = isset($product->review_link) ? $product->review_link : '';
		    $browser = Helpers::getBrowser();
		    if ($browser['code'] != 'Chrome') {
			    $is_add_review = 'error_browser';
		    } else {
		    	if($browser['platform'] == 'mobile') {
				    $is_add_review = 'error_browser';
			    } else {
                    $is_add_review = 'error_products'; // hết số lượng product được add 
                    $listReviewFromAli = $this->_commentBackendRepo->all(session('shopId'),[
                        'product_id' => $product->id,
                        'source' => ['aliexpress', 'oberlo','aliorder']
                    ]);
                    // $listProductHasReview = $this->_productRepo->getAll(session('shopId'), array( 'is_review' => 1 ));

                    if (!$shopPlanInfo['total_product']) {
                        $is_add_review = 'success';
                    }else{
                        // check for free
                    // if($listProductHasReview['status']) {
                    //     $listProductHasReview = $listProductHasReview['products'];
                    //     if(empty($listProductHasReview->total()) or ($listProductHasReview->total() < $shopPlanInfo['total_product'])) {
                    //         $is_add_review = 'success';
                    //     }
                    // }
                        $listProductsImpotedReviews = $this->_productRepo->getListProductsImportedReviews(session('shopId'),false);
                        $countProductImportedReview = count($listProductsImpotedReviews);
//                        $countProductImportedReview = $this->_productRepo->countReviewProduct(session('shopId'),'import');
                        //nếu product nằm trong list product đã import thì vẫn cho importe lại - daovs
                        if(in_array($product->id,$listProductsImpotedReviews)){
                            $is_add_review = 'success';
                        }else{
                            // nếu số luong products đã import ít hơn số lượng products được phép import thì vẫn cho import tiếp
                            if(empty($countProductImportedReview) or ($countProductImportedReview < $shopPlanInfo['total_product'])) {
                                $is_add_review = 'success';
                            }
                        }

                        $result['countProductImportedReview'] = $countProductImportedReview;
                    }

				    if ($listReviewFromAli->total()) {
                        $has_imported_review = true;
                    }
			    }
            }
            $result['status'] = true;
            $result['message'] = $is_add_review;
            $result['has_imported_review'] = $has_imported_review;
            $result['review_link'] = $review_link;
	    } else {
            $result['status'] = false;
            $result['message'] = $is_add_review;
        }
        return response()->json($result, 200);
    }
    public function checkVariableRedis()
    {
        $shopId = session('shopId');
        $popup2_1 = Redis::get($shopId.'_numberShowPopup2_1');
        $popup2_2 = Redis::get($shopId.'_numberShowPopup2_2');
        $popup3 = Redis::get($shopId.'_numberShowPopup3');
        $popup4 = Redis::get($shopId.'_numberShowPopup4');
        $popup5 = Redis::get($shopId.'_numberShowPopup5');
        $popup6 = Redis::get($shopId.'_numberShowPopup6');
        $numberSendEmail7 = Redis::get($shopId.'_numberSendEmail7');
        $useThemesSetting = Redis::get($shopId.'_themesSetting');
        echo "popup2_1: ".$popup2_1."<br>".
             "popup2_2: ".$popup2_2."<br>".
             "popup3: ".$popup3."<br>".
             "popup4: ".$popup4."<br>".
             "popup5: ".$popup5."<br>".
             "popup6: ".$popup6."<br>".
             "numberSendEmail7: ".$numberSendEmail7."<br>".
             "useThemesSetting: ".$useThemesSetting;
    }

    public function deleteAllVariableRedis()
    {
        $shopId = session('shopId');
        $popup2_1 = Redis::del($shopId.'_numberShowPopup2_1');
        $popup2_2 = Redis::del($shopId.'_numberShowPopup2_2');
        $popup3 = Redis::del($shopId.'_numberShowPopup3');
        $popup4 = Redis::del($shopId.'_numberShowPopup4');
        $popup5 = Redis::del($shopId.'_numberShowPopup5');
        $popup6 = Redis::del($shopId.'_numberShowPopup6');
        $numberSendEmail7 = Redis::del($shopId.'_numberSendEmail7');
        $useThemesSetting = Redis::del($shopId.'_themesSetting');
    }

    public function deleteVariableRedis($variable)
    {
        $shopId = session('shopId');
        Redis::del($variable);
    }

    public function setVariableRedis($variable)
    {
        $shopId = session('shopId');
        Redis::set($variable, 1);
    }

    public function checkVariablePopup2()
    {
        $shopId = session('shopId');
        $checkPopup2_1 = Redis::get($shopId . '_numberShowPopup2_1');
        $checkPopup2_2 = Redis::get($shopId . '_numberShowPopup2_2');
        $shopAppPlan = $this->_logRepo->checkAppPlan($shopId);
        $numberProductReview = $this->_productRepo->countReviewProduct($shopId);
        $oldPlan = $this->_logRepo->checkLog($shopId);
        $checkPopup4 = Redis::get($shopId . '_numberShowPopup4');
        $checkSendEmail7 = Redis::get($shopId . '_numberSendEmail7');
        $showPopup2 = false;
        if($shopAppPlan == 'premium' && empty($checkPopup4) && empty($checkSendEmail7)) {
            if($oldPlan == 'upgrade') {
                if($numberProductReview == 15 && empty($checkPopup2_1)) {
                    $showPopup2 = true;
                }
                if($numberProductReview == 20 && empty($checkPopup2_2)) {
                    $showPopup2 = true;
                }
            }
            else {
                if($numberProductReview == 5 && empty($checkPopup2_1)) {
                    $showPopup2 = true;
                }
                if($numberProductReview == 10 && empty($checkPopup2_2)) {
                    $showPopup2 = true;
                }
            }
        }
        echo "popup2_1: ".$checkPopup2_1."<br>".
             "popup2_2: ".$checkPopup2_2."<br>".
             "shopAppPlan: ".$shopAppPlan."<br>".
             "numberProductReview: ".$numberProductReview."<br>".
             "oldPlan: ".$oldPlan."<br>".
             "checkPopup4: ".$checkPopup4."<br>".
             "numberSendEmail7: ".$checkSendEmail7."<br>".
             "showPopup2: ".$showPopup2;
    }

}
