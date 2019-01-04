<?php

namespace App\Contract\ShopifyAPI;


interface OrdersApiInterface
{
    /**
     * @param array $field
     * @param int $limit
     * @param int $page
     * @return mixed
     */
    public function all(array $field, int $limit, int $page) : array ;

    /**
     * @param array $field
     * @param string $order
     * @return array
     */
    public function detail(array $field, string $order): array ;

    /**
     * @param string $status
     * @return array
     */
    public function count(string $status) : array ;

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data) : array ;

    /**
     * @param string $order
     * @param array $data
     * @return array
     */
    public function update(string $order, array $data) : array;

    /**
     * @param string $order
     * @return array
     */
    public function delete(string $order) : array ;
}