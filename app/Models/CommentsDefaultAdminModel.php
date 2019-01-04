<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopsModel
 * @package App\Models
 */
class CommentsDefaultAdminModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments_default_admin';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
	    'author',
	    'country',
	    'user_order_info',
	    'star',
	    'content',
	    'email',
	    'avatar',
	    'img',
	    'status',
	    'source',
	    'verified',
	    'pin',
	    'like',
	    'unlike',
    ];

}