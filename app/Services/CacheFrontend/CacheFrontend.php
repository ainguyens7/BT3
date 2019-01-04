<?php

namespace App\Services\CacheFrontend;

use Exception;
use Illuminate\Support\Facades\Redis;
use App\Contracts\CacheFrontend\CacheFrontendInterface;

class CacheFrontend implements CacheFrontendInterface
{
    private $redis;

    private $sentry;

    public function __construct()
    {
        $this->redis = Redis::connection('redis_cache_frontend');
        $this->sentry = app('sentry');
    }

    public function exist($hash = null, $field = null)
    {
        try {
            return $this->redis->hexists($hash, $field) && !is_null($this->get($hash, $field));
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
            return 0;
        }
    }

    public function del($hash = null)
    {
        try {
            return $this->redis->del($hash);
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
            return 0;
        }
    }

    public function store($hash = null, $field = null, $value = null)
    {
        try {
            $this->redis->hset($hash, $field, $value);
            $this->redis->expire($hash, config('cache_frontend.default_expire_cache'));
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
            return 0;
        }
        
    }

    public function get($hash = null, $field = null)
    {
        try {
            return $this->redis->hget($hash, $field);
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
            return null;
        }
    }

    public function findKey($hash = null)
    {
        try {
            return $this->redis->keys($hash);
        } catch (Exception $exception) {
            $this->sentry->captureException($exception);
            return [];
        }
    }
}
