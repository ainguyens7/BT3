<?php

namespace App\Services\Worker;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class WorkerClient
{
    private $apiOrigin;
    protected $client;
    /**
     * WorkerClient constructor.
     */
    public function __construct()
    {
        $host = config('worker.worker_host');
        $port = config('worker.worker_port');
        $this->apiOrigin = "{$host}:{$port}";
        $this->client = new Client([
            'base_uri' => $this->apiOrigin,
            'timeout' => 5,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * Use async to dispatch a job to Worker
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function post($data = [])
    {
        $res = $this->client->post('/job', [
            'json' => $data
        ]);
        return json_decode($res->getBody(), true);
    }
}
