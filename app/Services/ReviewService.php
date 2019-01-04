<?php

namespace App\Services;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\UpdateProductMetafieldJob;

class ReviewService 
{
    protected $shopId;

    protected $productId;

    protected $sentry;

    protected $commentFrontEndRepo;

    protected $shopRepo;

    protected $productRepo;

    protected $_commentRepo;

    public function __construct($shopId, $productId)
    {
        $this->shopId = $shopId;
        $this->productId = $productId;
        $this->sentry = app('sentry');
        $this->commentFrontEndRepo = RepositoryFactory::commentFrontEndRepository();
        $this->shopRepo = RepositoryFactory::shopsReposity();
        $this->productRepo = RepositoryFactory::productsRepository();
        $this->_commentRepo  = RepositoryFactory::commentBackEndRepository();

    }
    public function generateReviewBox()
    {
        $result = [
            'status' => false,
            'result' => ''
        ];

        $settings = Helpers::getSettings($this->shopId);
        
		if(empty($settings['active_frontend'])) {
            $result['result'] = 'No active front end';
            return $result;
        }
        $shopPlanInfo = $this->shopRepo->shopPlansInfo($this->shopId);
		if (!$shopPlanInfo['status']) {
            $result['result'] = 'Error when get plan of Shop';
            return $result;
        }
        $shopPlanInfo = $shopPlanInfo['planInfo'];
        $dataReview = $this->commentFrontEndRepo->getReviewProductFrontEnd($this->productId, $this->shopId, 1, null, []);
    
		$avgStar = $this->commentFrontEndRepo->getAvgStar($this->productId, $this->shopId);
		$totalReview = $this->commentFrontEndRepo->getTotalStar($this->productId, $this->shopId, null, [config('common.status.publish')]);
    
		//Get show meta
		$style = 2;
		$setting = isset($settings['setting']) ? ($settings['setting']) : [];
		if (empty($setting['section_show'])) {
			$setting['section_show'] = array();
		}
		$approve_review = isset($settings['approve_review']) ? $settings['approve_review'] : 1;
		$translate = isset($settings['translate']) ? $settings['translate'] : config('settings.translate');
		if(empty($settings['is_translate'])){
			$translate = config('settings.translate');
		}
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
        
        $statistic = $this->getStatisticReview($this->productId, $this->shopId);

		if ($dataReview) {
			$args_views = [
				'comments' => $dataReview,
				'total_review' => !empty($totalReview) ? $totalReview : 0,
				'avg_star' => $avgStar,
				'setting' => $setting,
				'translate' => $translate,
				'shop_id' => $this->shopId,
				'product_id' => $this->productId,
				'approve_review' => $approve_review,
				'shopPlanInfo' => $shopPlanInfo,
				'code_css' => $code_css,
				'style_customize' => $style_customize,
				'rating_point' => $rating_point,
                'rating_card' => $rating_card,
                'statistic' => $statistic
            ];
            
            $view = view($view_part, $args_views)->render();
            $result['status'] = true;
            $result['result'] = $view;
            return $result;
        } 
        return $result;
    }


    /**
	 * Get statistic review for product
	 *
	 * @param $productId
	 *
	 * @return array
	 */
	public function getStatisticReview($productId, $shopId) {
		$shopId    = $shopId;
		$statistic = array();

		$statistic['avg_star']     = $this->_commentRepo->getAvgStar( $productId, $shopId );
		$statistic['total_star']   = $this->_commentRepo->getTotalStar( $productId, $shopId );
		$statistic['total_star_5'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 5 );
		$statistic['total_star_4'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 4 );
		$statistic['total_star_3'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 3 );
		$statistic['total_star_2'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 2 );
		$statistic['total_star_1'] = $this->_commentRepo->getTotalStar( $productId, $shopId, 1 );
		if ( $statistic['total_star'] > 0 ) {
			$statistic['percent_star_5'] = ( $statistic['total_star_5'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_4'] = ( $statistic['total_star_4'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_3'] = ( $statistic['total_star_3'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_2'] = ( $statistic['total_star_2'] * 100 ) / $statistic['total_star'];
			$statistic['percent_star_1'] = ( $statistic['total_star_1'] * 100 ) / $statistic['total_star'];
		}

		$statistic['total_reviews'] = $this->_commentRepo->getTotalStatus( $productId, $shopId );

		return $statistic;
	}

    public function generateBadgeReview()
    {
        $result = [
            'status' => false,
            'result' => ''
        ];

        $settings = Helpers::getSettings($this->shopId);
        
		if(empty($settings['active_frontend'])) {
            $result['result'] = 'No active front end';
            return $result;
        }

        $productDetail = $this->productRepo->detail($this->shopId, $this->productId);

        if (is_null($productDetail)) {
            $productName = '';
            $productImage = '';
        } else {
            $productName = $productDetail->title;
            $productImage = $productDetail->image;
        }

        $shopPlanInfo = $this->shopRepo->shopPlansInfo($this->shopId);
		if (!$shopPlanInfo['status']) {
            $result['result'] = 'Error when get plan of Shop';
            return $result;
        }
        $translate = isset($settings['translate']) ? $settings['translate'] : config('settings.translate');
        $rating_point = isset($settings['rating_point']) ? $settings['rating_point'] : config('settings.rating_point');

        $avgStar = $this->commentFrontEndRepo->getAvgStar($this->productId, $this->shopId);
        $totalReview = $this->commentFrontEndRepo->getTotalStar($this->productId, $this->shopId, null, [config('common.status.publish')]);
        $viewPart = 'comment.single-rating';
        $viewPartScript = 'comment.single-rating-script';
        $reviewTitle = config('settings.translate.text_review_title');
        if ($totalReview != 1) {
            if (isset($translate['text_reviews_title'])) {
                $reviewTitle = $translate['text_reviews_title'];
            }
        } else {
            if (isset($translate['text_review_title'])) {
                $reviewTitle = $translate['text_review_title'];
            }
        }
        
        $view = view($viewPart, [
            'product_name' => $productName,
            'product_image' => $productImage,
            'product_rating_avg' => $avgStar, 
            'product_rating_total' => $totalReview,
            'product_style_rating' => $rating_point,
            'review_title' => $reviewTitle
        ])->render();

        $viewScript = view($viewPartScript, [
            'product_name' => $productName,
            'product_image' => $productImage,
            'product_rating_avg' => $avgStar,
            'product_rating_total' => $totalReview,
            'product_style_rating' => $rating_point,
            'review_title' => $reviewTitle
        ])->render();
        $result['status'] = true;
        $result['result'] = $view;
        $result['resultScript'] = $viewScript;
        return $result;
    }

    /**
     * Update reviewbox to product metafield
     *
     * @return void
     */
    public function updateWidgetReviewBox($shopifyDomain, $accessToken) {
        dispatch(new UpdateProductMetafieldJob($this->shopId, $shopifyDomain, $accessToken, $this->productId));
    }

    /**
     * Update review box when review updated
     *
     * @param String $shopId
     * @param String $productId
     * @param String|CommentsModel $commentData
     *
     * @return void
     */
    public static function updateWidgetReviewHandle($shopId, $productId, $commentData = null, $shopifyDomain, $accessToken) {
        // Case $productId is null
        if (!$productId) {
            // If $commentData is commentId
            if (!is_object($commentData)) {
                $commentRepo = RepositoryFactory::commentBackEndRepository();
                $commentData = $commentRepo->detail($shopId, $commentData);
            }

            // If $commentData is comment model
            if (!$commentData || empty($commentData->product_id)) {
                return;
            }
            $productId = $commentData->product_id;
        }

        // Case $productId has data
        $service = new ReviewService($shopId, $productId);
        $service->updateWidgetReviewBox($shopifyDomain, $accessToken);
    }
}
