<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class ShopifyService
 * @package App\Service
 */
class GeoIpService
{
    public static function GetGeoIp($ip) {
        $url = env('GEO_IP_API');
        $client = new Client();

        $response = $client->request(
            'GET',
            $url . "/geo?ip={$ip}",
            [
                'query' => ['ip' => $ip]
            ]
        );
        $result['country_code'] = 'US';
        $res = json_decode($response->getBody()->getContents(), true);
        if (isset($res['data']['country_code'])) {
            $result['country_code'] = $res['data']['country_code'];
        }
        return $result;
    }
}
