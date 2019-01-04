<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/countries', 'ApiController@getCountries');

Route::middleware(['shopInstall:api'])->group(function ()
{
    Route::post('/oberlo/deleteReviewsProduct', 'ApiController@delReviewsProduct');
    Route::get('/get_review_box/{shopName}/{productId}', 'ApiController@getReviewBox');
    Route::get('/get_review_badge/{shopName}/{productId}', 'ApiController@getReviewBadge');
    Route::get('/settings/{shopName}', 'ApiController@getSettings');
    Route::get('shops/{shopName}/products/{productId}', 'ApiController@getProductInfo');
    Route::put('shops/{shopName}/products/{productId}', 'ApiController@saveReviews');
    Route::post('/prepareImportReview', 'ApiController@prepareImportReview');
    Route::post('shops/{shopName}/products/bulk_exist_review', 'ApiController@bulkExistReview');
    Route::get('/shops/{shopName}', 'ApiController@getInfoShop');
    Route::get('/shops/settings_extension_aliorder/{shopName}', 'ApiController@getSettingsExtensionAliOrder');
});
Route::get('/ping', 'ApiController@healthyCheckApp'); 