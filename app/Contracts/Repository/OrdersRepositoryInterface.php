<?php

namespace Contract\Repository;

/**
 * Interface ProductsRepositoryInterface
 * @package Contract\Repository
 */
interface OrdersRepositoryInterface
{

	/**
	 * @param $orders
	 *
	 * @return mixed
	 */
	public function addMultiOrder($orders);

	/**
	 * @param $order
	 *
	 * @return bool
	 */
    public function addOrders( $order ) : bool ;
}