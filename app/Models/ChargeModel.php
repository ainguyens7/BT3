<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ChargeModel
 * @package App\Models
 */
class ChargeModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'charge';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [

    ];
}
