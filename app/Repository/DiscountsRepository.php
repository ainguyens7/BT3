<?php

namespace App\Repository;


use App\Models\DiscountsModel;


class DiscountsRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_model;

    function __construct()
    {
        $this->_model = new DiscountsModel();
    }

    public function getDiscountsByUser($shopDomain,$types = ['invited','special']){
	    $list = $this->_model->where('shop_name',$shopDomain)->whereIn('type',$types)->get();

	    return $list;
    }

    public function countInviteOfUser($shopDomain){
	    $count = $this->_model->where('shop_name',$shopDomain)->where('status',1)->where('type','invite')->count();

	    return $count;
    }

	public function detail( $shopName ) {
		return $this->_model->where( 'shop_name', $shopName )->first();
	}


	/**
	 * Lấy tổng discount của user hiện tại trước khi nhập mã giảm  giá
	 */
	public function getCurrentDiscountByUser($shopDomain){
		$discount = 0;
		//Lấy danh sách discount của shop không phải invite
		$listDiscountOfUser = $this->getDiscountsByUser($shopDomain);
		if(!empty($listDiscountOfUser)){
			foreach ($listDiscountOfUser as $v){
				$discount += $v->discount;
			}
		}

		// tổng số invite thành công của shop
		//$total_invite = $this->countInviteOfUser($shopDomain);
		// tính số discount trên tổng tống invite thành công.
		/*if(!empty($total_invite)){
			if($total_invite <= 5){
				$discount = $discount + config('discount')['invite']['1-5'];
			}else{
				if($total_invite <= 10){
					$discount = $discount + config('discount')['invite']['6-10'];
				}else{
					if($total_invite <= 15){
						$discount = $discount + config('discount')['invite']['11-15'];
					} else {
						$discount = $discount + config('discount')['invite']['16'];
					}
				}
			}
		}*/

		return $discount;
	}

	public function save($data){
		if(!empty($data['id'])){
			$id = $data['id'];
			unset($data['id']);
			$save  = $this->_model->where('id',$id)->update($data);
		}else{
			$save  = $this->_model->create($data);
		}
		return $save;
	}
}