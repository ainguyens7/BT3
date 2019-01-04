<?php

namespace Contract\Repository;

/**
 * Interface MembershipRepositoryInterface
 * @package Contract\Repository
 */
interface MembershipRepositoryInterface
{
    public function all(array $params = []);

    public function detail($object);

    public function insert($data) : array ;

	public function update($data) : array ;

	public function delete(int $id) : array ;
}