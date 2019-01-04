<?php

namespace App\Repository;


use App\Models\ReviewInfoModel;

/**
 * Class ReviewInfoRepository
 * @package App\Repository
 */
class ReviewInfoRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_reviewInfoModel;

    /**
     * ReviewInfoRepository constructor.
     */
    function __construct()
    {
        $this->_reviewInfoModel = new ReviewInfoModel();
    }

	/**
	 * Delete review info
	 *
	 * @param $shop_id
	 * @param $product_id
	 *
	 * @return bool|null
	 */
    public function delete($shop_id,$product_id){
	    return $this->_reviewInfoModel->where([
	    	'shop_id' => $shop_id,
	    	'product_id' => $product_id,
	    ])->delete();
    }
}