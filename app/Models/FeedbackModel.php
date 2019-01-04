<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopsModel
 * @package App\Models
 */
class FeedbackModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'feedback';

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
        'feedback',
        'status',
    ];
}