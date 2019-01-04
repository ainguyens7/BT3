<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class ProductsModel
 * @package App\Models
 */
class LogModel extends Model
{
    protected $table = 'log';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
	
	/**
	 * @var array
	 */
    protected $fillable = [
        'shop_id',
        'type',
	    'value'
    ];
	
	/**
	 * @var array
	 */
    protected $date = [
        'created_at',
        'updated_at'
    ];
}