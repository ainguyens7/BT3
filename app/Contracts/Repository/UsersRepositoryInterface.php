<?php
declare(strict_types=1);

namespace Contract\Repository;


interface UsersRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function register($data = ['email', 'password']) : array ;

    /**
     * @param array $data
     * @return mixed
     */
    public function login($data = ['email', 'password']) : array ;

    /**
     * @param string $email
     * @return mixed
     */
    public function forgotPassword(string $email) : array ;

    /**
     * @return mixed
     */
    public function logout() :bool ;

}