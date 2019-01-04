<?php

namespace App\Repository;

use App\Models\LogModel;
use App\Models\ShopsModel;

/**
 * Log type list
 *
 * free
 * premium
 * unlimited
 *
 * install
 * uninstall
 * //upgrade => unused
 * downgrade
 *
 * import_review
 * keyword_filter
 * review_page
 */

class LogRepository
{
    private $_logModel;

    /**
     * LogRepository constructor.
     */
    function __construct() {
        $this->_logModel = new LogModel();
    }

    public function save(array $arg)
    {
        $logModel = new LogModel();
        return $logModel->create($arg);
    }

    public function checkAppPlan($shopId)
    {
        $shop = ShopsModel::where('shop_id', '=', $shopId)->first();
        $appPlan = $shop->app_plan;
        return $appPlan;
    }

    public function checkLog($shopId)
    {
        $oldPlan = LogModel::where('shop_id', $shopId)->orderBy('created_at', 'desc')->orderBy('id', 'desc')
            ->pluck("type")->first();
        return $oldPlan;
    }

    public function checkFreeUser($shopId)
    {
        $result = false;
        $shop = ShopsModel::where('shop_id', '=', $shopId)->first();
        if(!empty($shop->app_plan) && $shop->app_plan == 'free') {
            $logAppPlan =  LogModel::where('shop_id', $shopId)->pluck('type')->toArray();
            if(!in_array("downgrade", $logAppPlan)) {
                $result = true;
            }
        }
        return $result;
    }
    /**
     * Get last log by type
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function getLastLog($shopId, $type)
    {
        return $this->_logModel->where('type', $type)
            ->where('shop_id', $shopId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get last log by types
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function getLastLogMulti($shopId, $types)
    {
        return $this->_logModel->whereIn('type', $types)
            ->where('shop_id', $shopId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
