<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewInfoModel
 * @package App\Models
 */
class ReviewInfoModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'review_info';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'product_id',
        'is_review',
        'review_info'
    ];
}
