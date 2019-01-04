<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopsModel
 * @package App\Models
 */
class DiscountsModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'discounts';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
	protected $fillable = [
		'id',
		'shop_name',
		'discount',
		'type',
		'shop_related',
		'status',
		'note',
	];
}