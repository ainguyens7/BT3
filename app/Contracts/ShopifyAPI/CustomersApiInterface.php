<?php

namespace App\Contracts\ShopifyAPI;


interface CustomersApiInterface
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
     * @param string $customer
     * @return array
     */
    public function detail(array $field, string $customer): array ;

    /**
     * @return array
     */
    public function count() : array ;

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data) : array ;

    /**
     * @param string $customer
     * @param array $data
     * @return array
     */
    public function update(string $customer, array $data) : array;

    /**
     * @param string $customer
     * @return array
     */
    public function delete(string $customer) : array ;
}