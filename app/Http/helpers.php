<?php
/**
 * Created by PhpStorm.
 * Các function xử lý chung
 * User: buicongdang
 * Date: 12/16/16
 * Time: 3:46 PM
 */

function get_http_response_code($url)
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}
function check_http_response_200($url)
{
    if(get_http_response_code($url) != '200')
        return false;

    return true;
}

function convertRateNumber($string_star) {
    $star = 0;
    preg_match_all('!\d+!', $string_star, $matches);
    if(isset($matches[0][0]))
        $rating = $matches[0][0];
    $star = $rating * 5/100;
    return $star;
}


function convertJsonImage($arr_img) {
    if( ! isset($arr_img[0]))
        return '';

    $arr_img = $arr_img[0];
    return json_encode($arr_img);
}

function convertOrderInfoJson($arr) {
    $data = [];
    if(empty($arr))
        return '';
    foreach ($arr as $k=>$v) {
        $v = strip_tags($v);
        $dt = explode(':', $v);
        if(isset($dt[0]) && strtolower($dt[0]) != 'logistics') {
          if(isset($dt[0]))
              $data[$k]['key'] = $dt[0];
          if(isset($dt[1]))
              $data[$k]['value'] = $dt[1];
        }
    }

    return json_encode($data);
}

function getNameStatus($status) {
    $status_name = '';
    switch ($status) {
        case config('custom.status.published'):
            $status_name = '<span class="label label-published">Published</span>';
            break;
        case config('custom.status.unpublished'):
            $status_name = '<span class="label label-unpublished">Unpublished</span>';
            break;

        case config('custom.status.trash'):
            $status_name = '<span class="label label-trash">Trash</span>';
            break;
        default:
            break;
    }

    return $status_name;
}

function getAvatar() {
    $arr = ['abstract','people'];
    $key = array_rand($arr);
    $id = rand(1,199);
    return 'images/avatar/'.$arr[$key].'/avatar'.$id.'.jpg';
}
function getAvatarAbstract() {
    $id = rand(1,199);
    return 'images/avatar/abstract/avatar'.$id.'.jpg';
}

function human_time_diff( $from, $to = '' ) {
    $since = '';
    if ( empty( $to ) ) {
        $to = time();
    }

    $diff = (int) abs( $to - $from );

    if ( $diff < config('custom.TIME_SECONDS.HOUR_IN_SECONDS')) {
        $mins = round( $diff / config('custom.TIME_SECONDS.MINUTE_IN_SECONDS') );
        if ( $mins <= 1 ) {
            $mins = 1;
            $since = $mins.' min';
        } else
            $since = $mins.' mins';
    } elseif ( $diff < config('custom.TIME_SECONDS.DAY_IN_SECONDS') && $diff >= config('custom.TIME_SECONDS.HOUR_IN_SECONDS') ) {
        $hours = round( $diff / config('custom.TIME_SECONDS.HOUR_IN_SECONDS') );
        if ( $hours <= 1 ) {
            $hours = 1;
            $since = $hours.' hour';
        } else
            $since = $hours.' hours';
    } elseif ( $diff < config('custom.TIME_SECONDS.WEEK_IN_SECONDS') && $diff >= config('custom.TIME_SECONDS.DAY_IN_SECONDS') ) {
        $days = round( $diff / config('custom.TIME_SECONDS.DAY_IN_SECONDS') );
        if ( $days <= 1 ) {
            $days = 1;
            $since = $days.' day';
        } else
            $since = $days.' days';
    } elseif ( $diff < config('custom.TIME_SECONDS.MONTH_IN_SECONDS') && $diff >= config('custom.TIME_SECONDS.WEEK_IN_SECONDS') ) {
        $weeks = round( $diff / config('custom.TIME_SECONDS.WEEK_IN_SECONDS') );
        if ( $weeks <= 1 ) {
            $weeks = 1;
            $since = $weeks.' week';
        } else
            $since = $weeks.' weeks';
    } elseif ( $diff < config('custom.TIME_SECONDS.YEAR_IN_SECONDS') && $diff >= config('custom.TIME_SECONDS.MONTH_IN_SECONDS') ) {
        $months = round( $diff / config('custom.TIME_SECONDS.MONTH_IN_SECONDS') );
        if ( $months <= 1 )
        {
            $months = 1;
            $since = $months.' month';
        } else
            $since = $months.' months';

    } elseif ( $diff >= config('custom.TIME_SECONDS.YEAR_IN_SECONDS') ) {
        $years = round( $diff / config('custom.TIME_SECONDS.YEAR_IN_SECONDS') );
        if ( $years <= 1 ) {
            $years = 1;
            $since = $years.' year';
        } else
            $since = $years.' years';
    }

    return $since. ' ago';
}

function getPageInSearchAliExpress($array) {
    if(empty($array))
        return 1;

    $pages = [];
    foreach ($array as $item) {
        $pages[] = intval($item);
    }
    return max($pages);
}


/**
 * Get product info in object product
 * @param $id
 * @param $products
 * @return bool
 */
function getProductInfoObjectProducts($id, $products) {
    if(empty($products))
        return false;

    foreach($products as $k=>$v) {
        if($v->id == $id)
            return $v;
    }
    return false;
}


function checkExistValue($array, $key, $compare) {
    if(empty($array))
        return false;

    foreach($array as $k=>$v) {
        if($v->$key == $compare)
            return true;
    }

    return false;
}

//CLear url link product detail aliexpress
function clearUrlLinkProductDetailALiexpress($source_link) {
  $new_link = $source_link;
  preg_match('/(https?)\:\/\/(wwww.?)aliexpress.com\/item\/[\w|-]*\/[0-9]*.html/i',$source_link, $args);
  return $args;
}
function getLangCodeInGetReviews($array_review) {
    $arrLangCode = [];
    if(empty($array_review))
        return $arrLangCode;

    foreach($array_review as $k=>$v) {
        $arrLangCode[$k] = $v['user_country'];
    }

    return array_unique($arrLangCode);
}
//Get list all country in file country json
function getCountryCode() {
    $file = storage_path('json/country.json');
    $country_code = [];
    if( ! file_exists($file))
        return $country_code;

    $source = file_get_contents($file);
    $country_code = json_decode($source, true);
    return $country_code;
}

//Get all country- convert key to value
function getCountryObjKeyToValue() {
  $country_code = getCountryCode();
  $data = [];
  foreach ($country_code as $key => $value) {
    $data[$key] = $value['Code'];
  }
  return $data;
}
//Convert value to key
function convertValueToKey($args) {
  $data = [];
  foreach ($args as $key => $value) {
    $data[$value] = $value;
  }
  return $data;
}

//Convert country json to array country
function getAllCountry() {
  $coutry_code = getCountryCode();
  $data = [];
  foreach ($coutry_code as $key => $value) {
      $data[$value['Code']] = $value['Name'];
  }
  return $data;
}


function getChoiceLang($array_code) {
    $args = [];
    $list_country_code = getCountryCode();

    foreach($list_country_code as $k=>$v) {
        if(in_array($v['Code'], $array_code))
            $args[] = $v;
    }
    return $args;
}

function curl_get_contents($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_PROXY, 'jp.proxymesh.com:31280');
  curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'dienpt:2baFVp1%AixX');
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function curl_get_content_proxy2($url) {
  $ip_proxy = get_radom_proxy();
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_PROXY, $ip_proxy);
  curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'dienpt2006:Phan2017');
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
/**
*Get radom proxy list
*/
function get_radom_proxy() {
    $file = storage_path('json/proxy_http_auth.txt');
    if( ! file_exists($file))
        return [];

   $list_proxy = file_get_contents($file);
   $list_proxy = explode(";",$list_proxy);
   $ran = rand (0,69);
   return $list_proxy[$ran];
}
//
function getIdProductAliexpress($link_aliexpress) {
  $product_id = '';
  preg_match('/\/[0-9]*.html/',$link_aliexpress, $args);
  if(isset($args[0]))
    $args = explode('.', $args[0]);

  if(isset($args[0]))
      $product_id = str_replace('/','',$args[0]);

  return $product_id;
}

//clear url aliexpress input
function clearUrlAliExpress($url) {
  $url = preg_replace("/\s+/","%20", $url);
  return preg_replace("/^https:\/\/es\.|^https:\/\/fr\./","https://", $url);
}


