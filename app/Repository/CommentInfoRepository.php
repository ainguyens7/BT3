<?php

namespace App\Repository;


use App\Models\CommentInfoModel;

/**
 * Class CommentInfoRepository
 * @package App\Repository
 */
class CommentInfoRepository {
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	protected $_model;


	/**
	 * CommentInfoRepository constructor.
	 */
	public function __construct() {
		$this->_model = new CommentInfoModel();
	}

	public function all($shop_id,$params){
		$query = $this->_model->where('shop_id',$shop_id);

		$perPage = isset( $params['perPage'] ) ? $params['perPage'] : config( 'common.pagination' );

		if(!empty($params['comment_id'])){
			$query->where('comment_id',$params['comment_id']);
		}

		if(!empty($params['ip_like'])){
			$query->where('ip_like',$params['ip_like']);
		}

		if(!empty($params['ip_unlike'])){
			$query->where('ip_unlike',$params['ip_unlike']);
		}

		$result = $query->orderBy( 'id', 'desc' )->paginate($perPage);

		return $result;
	}


	public function insertIpLike($shopId,$commentId,$ip){
		$args_save = [
			'shop_id' => $shopId,
			'comment_id' => $commentId,
			'ip_like' => $ip,
		];

		return $this->_model->create($args_save);
	}

	public function deleteIpLike($shopId,$commentId,$ip){
		return $this->_model->where('shop_id', $shopId)->where('comment_id',$commentId)->where('ip_like',$ip)->delete();
	}

	public function insertIpUnLike($shopId,$commentId,$ip){
		$args_save = [
			'shop_id' => $shopId,
			'comment_id' => $commentId,
			'ip_unlike' => $ip,
		];

		return $this->_model->create($args_save);
	}

	public function deleteIpUnLike($shopId,$commentId,$ip){
		return $this->_model->where('shop_id', $shopId)->where('comment_id',$commentId)->where('ip_unlike',$ip)->delete();
	}

}