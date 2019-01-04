<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopMetaModel
 * @package App\Models
 */
class ShopMetaModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'shop_meta';

    /**
     * @var string
     */
    protected $primaryKey = 'shop_id';

    /**
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'style',
        'setting',
        'translate',
        'is_translate',
        'is_comment_default',
        'rand_comment_default',
        'style_customize',
        'code_css',
        'page_reviews',
        'approve_review',
        'rating_point',
        'rating_card',
        'active_frontend',
        'is_code_css',
        'avatar'
    ];
}
