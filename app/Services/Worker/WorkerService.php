<?php

namespace App\Services\Worker;

class WorkerService
{
    /**
     * @var
     */
    private $_worker;
    private $_shopDomain;
    private $_accessToken;

    /**
     * WorkerService constructor.
     * @param $worker
     */
    public function __construct(WorkerClient $worker)
    {
        $this->_worker = $worker;
    }

    /**
     * Flexible call worker api, default method call method post
     * @param string $apiName
     * @param array $data
     * @return mixed
     */
    public function call($apiName, $data = [], $options = []) {
        $apiData = config("worker.$apiName");
        return $this->_worker->post([
            'type' => $apiData['type'],
            'data' => $data,
            'options' => $options
        ]);
    }
}
