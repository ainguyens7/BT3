<?php

namespace Contract\Repository;

/**
 * Interface CustomersRepositoryInterface
 * @package Contract\Repository
 */
interface CustomersRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data = []);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return mixed
     */
    public function  all();

    /**
     * @param $id
     * @return mixed
     */
    public function detail($id);

    /**
     * @param $order
     * @param $filter
     * @return mixed
     */
    public function filter($order, $filter);
}