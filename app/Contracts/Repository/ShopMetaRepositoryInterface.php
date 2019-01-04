<?php

namespace App\Contracts\Repository;

/**
 * Interface CustomersRepositoryInterface
 * @package App\Contracts\Repository
 */
interface ShopMetaRepositoryInterface {
	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function detail( $id );

	/**
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function create( array $data = [] );

	/**
	 * @param int $id
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function update( $id, array $data );

}