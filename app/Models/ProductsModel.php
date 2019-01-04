<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class ProductsModel
 * @package App\Models
 */
class ProductsModel extends Model
{
    protected $connection = 'mysql_product';
    /**
     * @var string
     */
    protected $table = 'products_';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'handle',
        'image',
        'review_link',
        'is_reviews',
        'created_at',
        'updated_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql_product');
    }
}