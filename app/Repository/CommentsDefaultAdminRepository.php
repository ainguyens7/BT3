<?php

namespace App\Repository;


use App\Models\CommentsDefaultAdminModel;
use App\Models\CommentsDefaultModel;

/**
 * Class ShopsRepository
 * @package App\Repository
 */
class CommentsDefaultAdminRepository
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $_commentsDefaultAdminModel;

    /**
     * ShopsRepository constructor.
     */
    function __construct()
    {
        $this->_commentsDefaultAdminModel = new CommentsDefaultAdminModel();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($params)
    {
	    $query = $this->_commentsDefaultAdminModel;

	    $perPage = isset( $params['perPage'] ) ? $params['perPage'] : config( 'common.pagination' );

	    $total = $query->count();

	    if(!is_numeric($perPage) && !empty($total)){
		    $result = $query->orderBy( 'created_at', 'desc' )->paginate($total);
	    }else{
		    $result = $query->orderBy( 'created_at', 'desc' )->paginate($perPage);
	    }

	    return $result;
    }
}