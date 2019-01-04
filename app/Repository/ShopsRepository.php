<?php

namespace App\Repository;

use Exception;

use App\Models\ShopsModel;

/**
 * Class ShopsRepository
 * @package App\Repository
 */
class ShopsRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
	private $_shopsModel;
	
	protected $sentry;

    /**
     * ShopsRepository constructor.
     */
    function __construct()
    {
		$this->_shopsModel = new ShopsModel();
		$this->sentry = app('sentry');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->_shopsModel->all();
    }

    /**
     * Delete shop record
     *
     * @param string shopId
     * @return boolean|null
     */
    public function delete($shopId)
    {
        if (!empty($shopId)) {
            $shopInfo = $this->_shopsModel->find($shopId);
            if($shopInfo){
                return $shopInfo->delete();
            }
        }
        return false;
    }

    /**
     * @param array $field
     * @return array
     */
    public function detail(array $field)
    {
		$shopId = session('shopId');

	    $this->sentry->user_context([
		    'shop_id' => $shopId
		]);

		try {
			$shopInfo = $this->_shopsModel->where($field)->first();
			if (empty($shopInfo)) {
				return ['status' => false, 'message' => 'Shop detail NULL'];
			}
			return ['status' => true, 'shopInfo' => $shopInfo];
        } catch (Exception $ex) {
        	$eventId = $this->sentry->captureException($ex);
            return ['status' => false, 'message' => "Error get shop detail. EventId: {$eventId}"];
        }
    }

    public function update(array $find = [], array $data = [])
    {
	    $this->sentry->user_context([
			'shop_id' => session('shopId'),
			'query' => $find,
			'update' => $data
		]);
	    try{
		    $update = $this->_shopsModel->where($find)->update($data);
		    return $update;
	    } catch (Exception $exception) {
		    $this->sentry->captureException($exception);
		    return false;
	    }

    }

    public function insert(array $data)
    {
	    $client =new \Raven_Client(env('SENTRY_DSN'));
	    $client->user_context(array(
		    'shop_id' => $data['shop_id'],
	    ));
	    try{
		    $shopModel = $this->_shopsModel->findOrNew($data['shop_id']);

		    foreach ($this->_shopsModel->getFillable() as $k=>$v)
		    {
			    if(key_exists($v, $data))
				    $shopModel->setAttribute($v, $data[$v]);
		    }

		    $isSave = $shopModel->save();
		    if($isSave)
			    return ['status' => true, 'message' => ''];
		    return ['status' => false, 'message' => 'Cannot save info shop'];

	    } catch (\Exception $exception)
	    {
		    $client->captureException($exception);
		    return ['status' => false, 'message' => $exception->getMessage()];
	    }

	}

	public function shopPlansInfo($shopId = '')
	{
	    $this->sentry->user_context(array(
		    'shop_id' => $shopId,
	    ));
	    try {
			$shopInfo = $this->_shopsModel
				->where(['shop_id' => $shopId])
				->first();
		    if ($shopInfo) {
		    	$plans_info = config('plans');
			    if(!empty($plans_info[$shopInfo->app_plan])) {
			    	return [
						'status' => true,
						'planInfo' => $plans_info[$shopInfo->app_plan]
					];
				}
		    }

		    return ['status' => false];
	    } catch (Exception $ex) {
		    $eventId = $this->sentry->captureException($ex);
		    return [
				'status' => false,
				'message' => "{$ex->getMessage()}. EventId: {$eventId}"
			];
	    }
    }
}