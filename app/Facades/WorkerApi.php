<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\WorkerApi\WorkerApi
 */
class WorkerApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'workerapi';
    }
}
