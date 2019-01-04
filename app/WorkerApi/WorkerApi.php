<?php

namespace App\WorkerApi;

use App\Services\Worker\WorkerService;
use App\Contracts\WorkerApi\WorkerApiInterface;

/**
 * Class WorkerApi
 */
class WorkerApi extends WorkerService implements WorkerApiInterface
{
    /**
     * @param string $apiName
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function callApi($apiName, $data = [], $options = []) {
        try {
            return $this->call($apiName, $data, $options);
        } catch (\Exception $exception) {
           // return throw exception, consumers will handle exception
           throw new \Exception($exception);
        }
    }
}
