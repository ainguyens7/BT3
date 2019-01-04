<?php

namespace App\Models;


use App\Events\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

/**
 * Class CommentsModel
 * @package App\Models
 */
class CommentInfoModel extends Model {

	/**
	 * @var string
	 */
	protected $table = 'comment_info';

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var array
	 */
	protected $fillable = [
		'id',
		'shop_id',
		'comment_id',
		'ip_like',
		'ip_unlike',
	];
}
