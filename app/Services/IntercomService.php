<?php

namespace App\Services;

use App\Factory\RepositoryFactory;
use Intercom\IntercomClient;

/**
 * Class ShopifyService
 * @package App\Service
 */
class IntercomService {
	private $_commentRepo;
	private $_productRepo;
	private $_shopRepo;
	private $_logRepo;
	private $_shopId;

	/**
	 * IntercomService constructor.
	 *
	 * @param $productRepo
	 */
	public function __construct( $shopId ) {
		$this->_commentRepo = RepositoryFactory::commentBackEndRepository();
		$this->_productRepo = RepositoryFactory::productsRepository();
		$this->_shopRepo    = RepositoryFactory::shopsReposity();
		$this->_logRepo     = RepositoryFactory::logRepository();
		$this->_shopId      = $shopId;
	}

	/**
	 * Get total review
	 *
	 * @return integer
	 */
	public function getTotalReviews() {
		// $listReviewsImport =  $this->_commentRepo->all(
		//     $this->_shopId,
		//     ['source' => ['aliexpress', 'oberlo']]
		// );
		// return $listReviewsImport ? $listReviewsImport->total() : 0;

		$total = $this->_commentRepo->countTotalReviewImported( $this->_shopId );

		return $total;
	}

	/**
	 * Get total reviewed products
	 *
	 * @return integer
	 */
	public function getTotalReviewedProducts() {
		// $filter['is_review'] = 1;
		// $products = $this->_productRepo->getAll($this->_shopId, $filter);
		// if ($products['status']) {
		//     return $products['products']->total();
		// }

		$total = $this->_productRepo->countReviewProduct( $this->_shopId );

		return $total;
	}

	/**
	 * Get shop last upgraded_at
	 *
	 * @return integer
	 */
	public function getShopUpgradedAt() {
		// $log = $this->_logRepo->getLastLogMulti($this->_shopId, [
		//     'premium',
		//     'unlimited',
		// ]);
		// if (!$log) {
		//     return 0;
		// }
		// return $log->created_at->timestamp;
		return 0;
	}

	/**
	 * Get shop last downgraded_at
	 *
	 * @return integer
	 */
	public function getShopDowngradedAt() {
		// $log = $this->_logRepo->getLastLog($this->_shopId, 'downgrade');
		// if (!$log) {
		//     return 0;
		// }
		// return $log->created_at->timestamp;
		return 0;
	}

	/**
	 * Get shop last uninstalled_at
	 *
	 * @return integer
	 */
	public function getShopUninstalledAt() {
		// $log = $this->_logRepo->getLastLog($this->_shopId, 'uninstall');
		// if (!$log) {
		//     return 0;
		// }
		// return $log->created_at->timestamp;
		return 0;
	}

	/**
	 * Check shop imported review or not
	 *
	 * @return boolean
	 */
	public function checkReviewImported() {
		// $log = $this->_logRepo->getLastLog($this->_shopId, 'import_review');
		// if (!$log) {
		//     return false;
		// }
		return TRUE;
	}

	/**
	 * Check shop used keyword_filter or not
	 *
	 * @return boolean
	 */
	public function checkKeywordFilter() {
		// $log = $this->_logRepo->getLastLog($this->_shopId, 'keyword_filter');
		// if (!$log) {
		//     return false;
		// }
		return TRUE;
	}

	/**
	 * Check shop used review_page or not
	 *
	 * @return boolean
	 */
	public function checkReviewPage() {
		// $log = $this->_logRepo->getLastLog($this->_shopId, 'review_page');
		// if (!$log) {
		//     return false;
		// }
		return TRUE;
	}

	public function createEvent( $eventName, $metadata = [] )
	{
		$client   = new IntercomClient( 'dG9rOmUyMzM1MDA5X2NhOTdfNDk0Nl85MzQ5XzhmZmZjNmE5NjUxYToxOjA=', NULL );
		$shopInfo = $this->_shopRepo->detail( [ 'shop_id' => $this->_shopId ] );
		if ( $shopInfo['status'] ) {
			$shopInfo = $shopInfo['shopInfo'];

			if(!empty($shopInfo->shop_email)){

				try {
					$client->users->getUsers(["email" => $shopInfo->shop_email]);
				} catch (\Exception $e) {
					if($e->getCode() === 404) {
						$client->users->create([
							"email" => $shopInfo->shop_email
						]);
					}
				}

				try {
					$client->events->create( [
						"event_name" => $eventName,
						"created_at" => time(),
						"email"      => $shopInfo->shop_email,
						//		    "metadata" => $metadata
					]);
				}catch (\Exception $e) {
					return FALSE;
				}

			}
		}

		return TRUE;
	}
}
