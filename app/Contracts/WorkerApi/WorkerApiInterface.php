<?php

namespace App\Contracts\WorkerApi;

interface WorkerApiInterface
{
    /**
     * @param string $type
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function callApi($type, $data = [], $options = []);
}
