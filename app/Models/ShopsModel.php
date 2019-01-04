<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * Class ShopsModel
 * @package App\Models
 */
class ShopsModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'shop';

    /**
     * @var string
     */
    protected $primaryKey = 'shop_id';

    /**
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'shop_name',
        'shop_email',
        'shop_status',
        'shop_country',
        'shop_owner',
        'plan_name',
        'app_version',
        'init_app',
        'billing_id',
        'billing_on',
        'cancelled_on',
        'access_token',
        'app_plan',
        'trial_date',
        'are_trial',
    ];

    protected $hidden = [
        'access_token'
    ];

    public function getDayUserJoined()
    {
        $createdAt = $this->created_at;
        $timeCreatedAt = new DateTime($createdAt);
        $timeNow = new DateTime('NOW');
        $diff = $timeNow->diff($timeCreatedAt);
        if (!$diff) {
            return 0;
        }
        if (!empty($diff->days)) {
            return $diff->days;
        }
        return 0;
    }
}