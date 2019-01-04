<?php

namespace Contract\Repository;

/**
 * Interface ShopsRepositoryInterface
 * @package Contract\Repository
 */
interface ShopsRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data);

    /**
     * @param array $find
     * @param array $data
     *
     * @return mixed
     */
    public function update(array $find = [], array $data = []);

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return mixed
     */
    public function  all();

    /**
     * @param array
     * @return mixed
     */
    public function detail(array $field);
}
