<?php

namespace App\Http\Controllers;


use App\Events\SaveIntercomEvent;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\ShopifyApi\PageApi;
use Illuminate\Http\Request;

class PageController extends Controller {

	private $_pageRepo;
	private $_pageApi;
	private $_shopRepo;

	public function __construct( RepositoryFactory $factory ) {
		$this->_pageRepo = $factory::pageRepository();
		$this->_pageApi  = new PageApi();
		$this->_shopRepo = $factory::shopsReposity();
	}

	public function createReviewsPage() {
		$shopPlanInfo = $this->_shopRepo->shopPlansInfo( session( 'shopId' ) );
		if ( $shopPlanInfo['status'] ) {
			$shopPlanInfo = $shopPlanInfo['planInfo'];
		}

		if ( empty( $shopPlanInfo['reviews_page'] ) ) {
			return redirect(route('apps.upgrade'));
		}

		$settings     = Helpers::getSettings( session( 'shopId' ) );
		$page_reviews = array();
		if ( ! empty( $settings['page_reviews'] ) ) {
			$this->_pageApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );
			$page_reviews = $this->_pageApi->detail( $settings['page_reviews'] );
			if ( $page_reviews['status'] ) {
				$page_reviews = $page_reviews['page'];
			}else{
				$page_reviews = array();
			}
		}

		return view( 'page.create_reviews_page', compact( 'page_reviews' ) );
	}

	public function createReviewsPageHandle( Request $request ) {
		$result = array(
			'status'  => false,
			'message' => 'An error, please try again.'
		);

		$data              = $request->all();
		$data['body_html'] = '<div id="shopify-ali-review" product-id="0" products-not-in=""><div class="shop_info" shop-id="'.session("shopId").'"><div class="reviews"></div></div></div>';

		$this->_pageApi->getAccessToken( session( 'accessToken' ), session( 'shopDomain' ) );
		$create = $this->_pageApi->create( $data );

		if ( $create['status'] ) {
			$page = $create['page'];

			$save = $this->_pageRepo->savePageReviews( $page->shop_id, $page->id );
			if ( $save ) {
				$result = [
					'status'   => 'success',
					'message'  => 'Created page successful',
					'page_url' => 'https://' . session( 'shopDomain' ) . '/pages/' . $page->handle,
				];

				event(new SaveIntercomEvent($page->shop_id,'created-testimonial'));
			}
		}

		return $result;
	}
}