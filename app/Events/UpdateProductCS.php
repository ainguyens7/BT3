<?php
/**
 * Created by PhpStorm.
 * User: fireapps
 * Date: 20/11/2018
 * Time: 10:57
 */

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;;

class UpdateProductCS
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shopId;

    public $shopDomain;

    public $accessToken;

    /**
     * UpdateProductCS constructor.
     * @param string $shopId
     * @param string $shopDomain
     * @param string $accessToken
     */
    public function __construct($shopId = '', $shopDomain = '', $accessToken = '')
    {
        $this->shopId = $shopId;
        $this->shopDomain = $shopDomain;
        $this->accessToken = $accessToken;
    }




}