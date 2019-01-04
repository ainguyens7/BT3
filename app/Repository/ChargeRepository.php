<?php

namespace App\Repository;


use App\Models\ChargeModel;

/**
 * Class ChargeRepository
 * @package App\Repository
 */
class ChargeRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_chargeModel;

    /**
     * ChargeRepository constructor.
     */
    public function __construct()
    {
        $this->_chargeModel = app(ChargeModel::class);
    }
}