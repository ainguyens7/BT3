<?php

namespace App\Repository;


use App\Models\CommentsDefaultAdminModel;
use App\Models\CommentsDefaultModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

/**
 * Class ShopsRepository
 * @package App\Repository
 */
class CommentsDefaultRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_commentsDefaultModel;

    /**
     * ShopsRepository constructor.
     */
    function __construct()
    {
        $this->_commentsDefaultModel = new CommentsDefaultModel();
    }

	/**
	 * @param $shopId
	 * @param $params
	 *
	 * @return LengthAwarePaginator|\Illuminate\Support\Collection
	 */
	public function all($shopId,$params)
	{
		$query = $this->_commentsDefaultModel->where('shop_id',$shopId);

		$perPage = isset( $params['perPage'] ) ? $params['perPage'] : config( 'common.pagination' );

		if(!empty($params['is_random'])){
			$query = $query->inRandomOrder();
		}

		$total = $query->count();

		if(!is_numeric($perPage) && !empty($total)){
			$result = $query->orderBy( 'created_at', 'desc' )->paginate($total);
		}else{
			$result = $query->orderBy( 'created_at', 'desc' )->paginate($perPage);
		}

		return $result;
	}

	/**
	 * @param $shopId
	 * @param $params
	 *
	 * @return LengthAwarePaginator|\Illuminate\Support\Collection
	 */
    public function allFrontEnd($shopId,$params)
    {

	    $query = $this->_commentsDefaultModel->where('shop_id',$shopId);
	    $perPage = isset( $params['perPage'] ) ? $params['perPage'] : config( 'common.pagination' );
	    $currentPage = isset( $params['currentPage'] ) ? $params['currentPage'] : 1;

	    $query->inRandomOrder();

	    $offset = 0;
	    if($currentPage > 1){
		    $offset = ($currentPage -1) * $perPage;
	    }

	    $limit = !empty($params['limit']) ? $params['limit'] : 0;
	    $total = $query->count();

	    if( $currentPage > 1){
		    if(($perPage * $currentPage > $limit)){
			    $query->offset($offset)->limit($perPage * $currentPage - $limit);
		    }else{
			    $query->offset($offset)->limit($perPage);
		    }
	    }else{
		    if(($perPage > $limit)){
			    $query->offset($offset)->limit($limit);
		    }else{
			    $query->offset($offset)->limit($perPage);
		    }
	    }

	    $result = $query->orderBy( 'id', 'desc' )->get();

	    if($total > $limit){
		    $total = $limit;
	    }

	    $result = [
	    	'data' => new LengthAwarePaginator($result, $total, $perPage,$currentPage),
		    'total' => $total,
	    ] ;


	    return $result;
    }

	public function detail($id){
		return $this->_commentsDefaultModel->where('id',$id)->first();
	}


	public function save($data){
		$id = $data['id'];
		unset($data['id']);

		$data_default = array(
			'source' => 'web',
			'verified' => 1,
			'status' => config('common.status.publish'),
		);
		$data = array_merge($data_default,$data);

		if(!empty($id)){
			$save  = $this->_commentsDefaultModel->where('id',$id)->update($data);
		}else{
			$save  = $this->_commentsDefaultModel->create($data);
		}

		return $save;
	}

	public function delete($id){
		return $this->_commentsDefaultModel->where('id',$id)->delete();
	}

	public function deleteAll($shopId){
		return $this->_commentsDefaultModel->where('shop_id',$shopId)->delete();
	}
}