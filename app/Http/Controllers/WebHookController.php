<?php

namespace App\Http\Controllers;


use App\Events\AddGoogleRatingEvent;
use App\Events\LogRecordEvent;
use App\Events\SaveIntercomEvent;
use App\Factory\RepositoryFactory;
use App\Helpers\Helpers;
use App\Jobs\SendMailUninstall;
use App\Jobs\SendMailUninstallTrial;
use App\Jobs\UpdateProductFromWebhook;
use App\ShopifyApi\ThemesApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Events\ProductCreated;
use App\Services\ShopifyWebHookService;

class WebHookController extends Controller {
	private $_shopRepo;
	private $_commentRepo;
	private $_reviewInfoRepo;
	private $_productRepo;

	protected $sentry;

	public function __construct( RepositoryFactory $factory ) {
		$this->_shopRepo       = $factory::shopsReposity();
		$this->_commentRepo    = $factory::commentBackEndRepository();
		$this->_reviewInfoRepo = $factory::reviewInfoRepository();
		$this->_productRepo    = $factory::productsRepository();
		$this->sentry = app('sentry');
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	public function uninstallApp(Request $request) {
		$res = $request->all();
		if ($header_hmac = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256')) {
			$data     = file_get_contents( 'php://input' );
			$verified = $this->verifyWebhook( $data, $header_hmac );
			if ( $verified ) {
				event( new LogRecordEvent( $res['id'], 'uninstall' ) );

				$shopInfo = $this->_shopRepo->detail( [ 'shop_id' => $res['id'] ] );
				if ( $shopInfo['status'] ) {
					$shopInfo = $shopInfo['shopInfo'];

					$update = $this->_shopRepo->update(
						[ 'shop_id' => $res['id'] ],
						[
							'shop_status'  => config( 'common.status.unpublish' ),
							'billing_id'   => '',
							'access_token' => '',
							'billing_on'   => '',
							'cancelled_on' => date( 'Y-m-d H:i:s', time() ),
							'updated_at'   => date( 'Y-m-d H:i:s', time() ),
						] );
					if ( ! $update ) {
						// Helpers::saveLog( 'error', array(
						// 	'message' => 'Uninstall App not update status',
						// 	'domain'  => $res['domain'],
						// ) );
						$this->sentry->captureMessage('Uninstall App not update status', array(
							'extract' => $res
						));
					}

					// kiểm tra user còn trong chu kỳ trial hay không
					if ( ! empty( $shopInfo->are_trial ) ) {
						$this->_shopRepo->update( [ 'shop_id' => $res['id'] ], [ 'are_trial' => 0 ] );
						// send mail uninstall with discount code
						$jobSendMail = ( new SendMailUninstallTrial( $res['id'] ) );
					} else {
						//send mail uninstall
						$jobSendMail = ( new SendMailUninstall( $res['id'] ) )->delay( Carbon::now()->addDay( 3 ) );
					}
					$this->dispatch( $jobSendMail );

					event(new SaveIntercomEvent($res['id'],'uninstalled-review'));
					// save cache last billing id for shop
					Cache::forever("{$shopInfo->shop_id}_lastBillingId", $shopInfo->billing_id);

					session( [ 'accessToken' => null ] );
				}

			} else {

				// Helpers::saveLog( 'error', [
				// 	'message' => 'Webhook uninstall not verify',
				// 	'domain'  => $res['domain']
				// ] );
				$this->sentry->captureMessage('Webhook uninstall not verify', array(
					'extract' => $res
				));
			}
		} else {
			// Helpers::saveLog( 'error', [ 'message' => 'Webhook uninstall not verify', 'domain' => $res['domain'] ] );
			$this->sentry->captureMessage('Not exists header HTTP_X_SHOPIFY_HMAC_SHA256', array(
				'extract' => $res
			));
		}
		return response()->json([], 200);
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	public function deleteProduct( Request $request ) {
		$res = $request->all();
		if ( $header_hmac = $request->server( 'HTTP_X_SHOPIFY_HMAC_SHA256' ) ) {
			$data     = file_get_contents( 'php://input' );
			$verified = $this->verifyWebhook( $data, $header_hmac );
			if ( $verified ) {
				$shop_name  = $request->server( 'HTTP_X_SHOPIFY_SHOP_DOMAIN' );
				$objectShop = $this->_shopRepo->detail( array( 'shop_name' => $shop_name ) );
				if ( $objectShop['status'] ) {
					$shop_info  = $objectShop['shopInfo'];
					$shopDomain = $shop_info->shop_name;
					$token = $shop_info->access_token;
					$shop_id    = $shop_info->shop_id;
					$product_id = $res['id'];
					$deleted = $this->_productRepo->delete( $shop_id, $res['id'] );
					$this->_commentRepo->deleteCommentByProduct( $shop_id, $product_id );
					$this->_reviewInfoRepo->delete( $shop_id, $product_id );
				}
			} else {
				$this->sentry->captureMessage('Webhook delete not verify', array(
					'extract' => $res
				));
			}
		} else {
			$this->sentry->captureMessage('Not exists header HTTP_X_SHOPIFY_HMAC_SHA256', array(
				'extract' => $res
			));
		}
		return response()->json([], 200);
	}

	public function createdProduct( Request $request ) {
		$res = $request->all();
		if ( $header_hmac = $request->server( 'HTTP_X_SHOPIFY_HMAC_SHA256' ) ) {
			$data     = file_get_contents( 'php://input' );
			$verified = $this->verifyWebhook( $data, $header_hmac );
			if ( $verified ) {
				$shop_name  = $request->server( 'HTTP_X_SHOPIFY_SHOP_DOMAIN' );
				$objectShop = $this->_shopRepo->detail( array( 'shop_name' => $shop_name ) );
				if ( $objectShop['status'] ) {
					$shop_info = $objectShop['shopInfo'];
					$shop_id   = $shop_info->shop_id;
					$create    = $this->_productRepo->create( $shop_id, $res );
					if (!$create) {
						$this->sentry->captureMessage('Webhook created product error', array(
							'extract' => $res
						));
					}

					// product created
					$productId = $res['id'];
					$shopDomain = $shop_info->shop_name;
					$token = $shop_info->access_token;
					event(new ProductCreated($shop_id, $shopDomain, $productId, $token));
				}
			} else {
				$this->sentry->captureMessage('Webhook created product not verify', array(
					'extract' => $res
				));
			}
		} else {
			$this->sentry->captureMessage('Not exists header HTTP_X_SHOPIFY_HMAC_SHA256', array(
				'extract' => $res
			));
		}
		return response()->json([], 200);
	}

	public function updatedProduct( Request $request ) {
		$res = $request->all();
		if ( $header_hmac = $request->server( 'HTTP_X_SHOPIFY_HMAC_SHA256' ) ) {
			$data     = file_get_contents( 'php://input' );
			$verified = $this->verifyWebhook( $data, $header_hmac );
			if ( $verified ) {
				$shop_name  = $request->server( 'HTTP_X_SHOPIFY_SHOP_DOMAIN' );
				$objectShop = $this->_shopRepo->detail( array( 'shop_name' => $shop_name ) );
				// $objectShop = ['status' => false];
				if ( $objectShop['status'] ) {
					$shop_info = $objectShop['shopInfo'];
					$shop_id   = $shop_info->shop_id;
					$update = $this->_productRepo->import($shop_id, array($res));
					if(!$update){
						$this->sentry->captureMessage('Updated product error', array(
							'extract' => $res
						));
					}
					// $this->dispatch( new UpdateProductFromWebhook( $shop_id, $res ) );
				}
			} else {
				$this->sentry->captureMessage('Webhook update product not verify', array(
					'extract' => $res
				));
			}
		} else {
			$this->sentry->captureMessage('Not exists header HTTP_X_SHOPIFY_HMAC_SHA256', array(
				'extract' => $res
			));
		}
		return response()->json(['status' => 'success'], 200);
	}

	public function themesUpdate(Request $request) {
        Log::info('Theme update webhook requested');
        $shopName  = $request->server( 'HTTP_X_SHOPIFY_SHOP_DOMAIN' );
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        ShopifyWebHookService::handleThemeUpdate($shopName, $hmacHeader);
	}


	public function themesPublish(Request $request) {
		Log::info('Theme publish webhook requested');
        $shopName  = $request->server( 'HTTP_X_SHOPIFY_SHOP_DOMAIN' );
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        ShopifyWebHookService::handleThemePublish($shopName, $hmacHeader);
	}

    /**
     * Duplicated with @verifyRequest
     * At App\Services\ShopifyWebHookService:18
     */
    /**
     * Handle customers redact
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customersRedact(Request $request) {
        Log::info('Webhook customers redact requested');
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        ShopifyWebHookService::handleCustomersRedact($hmacHeader);
        return response()->json([], 200);
    }

    /**
     * Handle shop redact
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shopRedact(Request $request) {
        Log::info('Webhook shop redact requested');
        $shopId  = $request->input('shop_id');
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        ShopifyWebHookService::handleShopRedact($shopId, $hmacHeader);
        return response()->json([], 200);
    }

	/**
	 * @param $data
	 * @param $hmac_header
	 *
	 * @return bool
	 */
	private function verifyWebhook( $data, $hmac_header ) {
		$calculated_hmac = base64_encode( hash_hmac( 'sha256', $data, env( 'API_SECRET' ), true ) );

		return ( $hmac_header == $calculated_hmac );
	}


}