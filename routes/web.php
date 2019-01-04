<?php
/**
 *
 */
Route::group([], function (){
	Route::get('install', 'AppsController@installApp')->name('apps.installApp');
	Route::post('installHandle', 'AppsController@installAppHandle')->name('apps.installHandle');
	Route::get('auth', 'AppsController@auth')->name('apps.auth');
	Route::get('get_started', 'AppsController@getStart')->name('apps.getStart');
	Route::get('charge_successful', 'ChargedController@chargeSuccessful')->name('apps.charge_successful')->middleware('not.allow.null.shop.id.and.access.token');
	// Route::get('charge_thank', 'ChargedController@chargeThank')->name('apps.charge_thank');
	// Route::get('installation_successful', 'AppsController@successInstall')->name('apps.success_install');
	//Handle reviews app at appsStore
	Route::post('review_app', 'AppsController@reviewApp')->name('apps.review_app');

	Route::get('email/unSubscribe', 'AppsController@emailUnSubscribe')->name('email.unsub');
	Route::get('email/unUseReviewPage', 'EmailsController@unUSeReviewPage')->name('email.unReP');
	Route::get('email/freeToUnlimitedPlan', 'EmailsController@freeToUnlimitedPlan')->name('email.freeToUnlimited');
});

Route::group([], function () {
	Route::get('choose-plan', 'AppsController@choicePlan')->name('apps.choicePlan')->middleware('allow.new.user');
});

/**
 *
 */
Route::group(['middleware' => 'shopify.check'], function (){
	Route::get('addCharged/{app_plan}', 'ChargedController@addCharged')->name('apps.addCharged');
	Route::get('planInfo', 'AppsController@planInfo')->name('apps.planInfo');
	Route::post('addDiscount', 'ChargedController@addDiscountHandle')->name('apps.addDiscountHandle');
	Route::get('chargedHandle', 'ChargedController@chargeHandle')->name('apps.chargeHandle');
	Route::get('checkDiscount/{discount_code}', 'ChargedController@checkDiscount')->name('apps.checkDiscount');

	// trial day
	Route::get('trial-premium-for-free-user', 'AppsController@trialDay')->name('apps.trialDay');
	Route::post('trial-premium-for-free-user', 'AppsController@trialDayHandle')->name('apps.trialDayHandle');
	Route::get('addChargedWithTrial/{app_plan}/{trial}', 'ChargedController@addChargedWithTrial')->name('apps.addChargedWithTrial');
	Route::get('trial-unlimited-for-free-user/{shop_domain}/{app_plan}/{trial}', 'ChargedController@trialFreeToUnlimited')->name('apps.trialFreeToUnlimited');

});

/**
 *
 */
Route::group(['middleware' => ['shopify.check', 'charged.check', 'shopify.checkVersionApp']], function() {
	//Dashboard
	Route::get('', 'DashboardController@index')->name('apps.dashboard');
	Route::get('dashboard', 'ProductsController@listProduct')->name('apps.dashboardPage');

	Route::get('manage-reviews', 'ProductsController@listProduct')->name('products.list');
	Route::post('saveReviewAliexpress', 'GetReviewController@saveReviewAliexpress')->name('get_review.save_review_aliexpress');
	Route::get('checkAddReview', 'GetReviewController@checkAddReview')->name('get_review.checkAddReview');

    // Route::get('cs/import-product', 'CustomerServicesController@import')->name('cs.importProduct');
    // Route::post('cs/import-product', 'CustomerServicesController@importHandle')->name('cs.importProductHandle');
});
/**
 *
 */
Route::group(['middleware' => 'shopify.check'], function() {

	// Settings
	Route::get('settings', 'SettingsController@settings')->name('settings.view');
	Route::post('settings', 'SettingsController@settingsHandle')->name('settings.handle');
	// Route::get('settings/comments-default', 'CommentsDefaultController@manage')->name('settings.commentsDefault');
	
	Route::post('settings/status-comments-default', 'CommentsDefaultController@statusCommentsDefault')->name('settings.statusCommentsDefault');
	Route::get('settings/comments-default-detail', 'CommentsDefaultController@detail')->name('settings.detailCommentsDefault');
	Route::post('settings/save-comments-default', 'CommentsDefaultController@save')->name('settings.saveCommentsDefault');
	Route::post('settings/delete-comments-default', 'CommentsDefaultController@delete')->name('settings.deleteCommentsDefault');
	Route::post('settings/delete-imported-reviews', 'CommentsDefaultController@deleteImportedReviews')->name('settings.deletedImportedReviews');
	Route::post('settings/random-comments-default', 'CommentsDefaultController@saveRandom')->name('settings.commentsDefault.saveRandom');
	Route::post('settings/import-comments-default', 'CommentsDefaultController@importToProducts')->name('settings.commentsDefault.importToProducts');
	Route::post('settings/status-local-translate', 'SettingsController@statusLocalTranslate')->name('settings.statusLocalTranslate');
	Route::post('settings/reset-local-translate', 'SettingsController@resetLocalTranslate')->name('settings.resetLocalTranslate');
	Route::get('settings/themes-setting', 'SettingsController@themesSetting')->name('settings.themesSetting');
	Route::post('settings/themes-setting', 'SettingsController@themesSettingHandle')->name('settings.themesSettingHandle');
	Route::post('settings/reset-themes-settings', 'SettingsController@resetThemesSettings')->name('settings.resetThemesSettings');
	Route::get('settings/themes-store', 'SettingsController@themesStore')->name('settings.themesStore');
	Route::post('settings/themes-store', 'SettingsController@themesStoreHandle')->name('settings.themesStoreHandle');
    Route::post('translation', 'SettingsController@localTranslateHandle')->name('settings.localTranslateHandle');
    Route::get('translation', 'SettingsController@localTranslate')->name('settings.localTranslate');
	Route::get('update', 'AppsController@updateApp')->name('apps.updateApp');
	Route::post('updateHandle', 'AppsController@updateAppHandle')->name('apps.updateAppHandle');
	Route::get('updateThemes', 'AppsController@updateThemes')->name('apps.updateThemes');
	Route::post('updateThemesHandle', 'AppsController@updateThemesHandle')->name('apps.updateThemesHandle');

	Route::get('pricing', 'AppsController@upgrade')->name('apps.upgrade');
	Route::post('upgradeHandle', 'AppsController@upgradeHandle')->name('apps.upgradeHandle');
	Route::get('reinstall', 'AppsController@confirmUpgrade')->name('apps.confirmUpgrade');
	Route::post('removeDataHandle', 'AppsController@removeDataHandle')->name('apps.removeDataHandle')->middleware('is.ajax.request');

	Route::post('feedback/save', 'FeedbackController@save')->name('feedback.save');
	
    Route::get('cs/update-themes', 'CSController@updateThemeCS')->name('cs.updateThemeCS');
    Route::post('cs/update-themes', 'CSController@handleUpdateThemeCS')->name('cs.updateThemeCS');
    Route::get('cs/update-settings', 'CSController@updateSettingAppCS')->name('cs.updateSettingAppCS');
    Route::post('cs/update-settings', 'CSController@handleUpdateSettingAppCS')->name('cs.updateSettingAppCS');
    Route::get('cs/update-products', 'CSController@updateProductsCS')->name('cs.updateProductsCS');
    Route::post('cs/update-products', 'CSController@handleUpdateProductsCS')->name('cs.updateProductsCS');
    Route::get('cs/update-master', 'CSController@updateMasterCS')->name('cs.updateMasterCS');
    Route::post('cs/update-master', 'CSController@handleUpdateMasterCS')->name('cs.updateMasterCS');
});
/**
 *
 */
Route::group(['middleware' => 'shopify.check'], function() {
	Route::get('manage-reviews/pending', 'ReviewsBackendController@reviewApprove')->name('reviews.review_approve');
	Route::get('manage-reviews/approved', 'ReviewsBackendController@reviewsHistory')->name('reviews.history_reviews');
	Route::get('reviews', 'ReviewsBackendController@manageAllReview')->name('reviews.list');
	Route::get('manage-reviews/{productId}', 'ReviewsBackendController@manageProductReview')->name('reviews.product');
	Route::get('review-info', 'ReviewsBackendController@reviewInfo');
	Route::post('review/pin', 'ReviewsBackendController@reviewPin');
	Route::post('review/delete', 'ReviewsBackendController@reviewDelete');
	Route::post('review/update', 'ReviewsBackendController@reviewUpdate');
	Route::post('review/approve', 'ReviewsBackendController@reviewApproveHandel');
	Route::post('review/change-status', 'ReviewsBackendController@reviewChangStatus')->middleware('is.ajax.request');
	Route::post('review/multi-action', 'ReviewsBackendController@multiReviewAction')->middleware('is.ajax.request');
	Route::post('review/all-action', 'ReviewsBackendController@allReviewAction')->middleware('is.ajax.request');
	Route::post('review/delete-reviews-in-shop', 'ReviewsBackendController@deleteReviewsInShopAction')->middleware('is.ajax.request');
	Route::post('review/uploadfile', 'ReviewsBackendController@uploadFile');
	Route::post('review/deletefile', 'ReviewsBackendController@deleteFile');
	Route::get('create-reviews-page', 'PageController@createReviewsPage')->name('reviews.createReviewsPage');
	Route::post('create-reviews-page', 'PageController@createReviewsPageHandle')->name('reviews.createReviewsPageHandle');
});
Route::group(['middleware' => ['cors.check', 'allow.review.frontend']], function(){
	//Review Front End
	Route::post('/comment/get_review', 'ReviewsFrontendController@getReview')->name('comment.getReview.frontEnd');
	Route::get('/comment/get_review', 'ReviewsFrontendController@getReview')->name('comment.getReview.frontEnd_get');
	Route::get('/comment/get_form/{shop}/{product}', 'ReviewsFrontendController@getForm');
	Route::post('/comment/post_review', 'ReviewsFrontendController@postReview')->name('comment.postReview.frontEnd');
	Route::get('/comment/post_review', 'ReviewsFrontendController@postReview')->name('comment.postReview.frontEnd_get');
	Route::post('/comment/get_review_pagination', 'ReviewsFrontendController@getReviewPagination')->name('comment.getReviewPagination.frontEnd');
	Route::get('/comment/get_review_pagination', 'ReviewsFrontendController@getReviewPagination')->name('comment.getReviewPagination.frontEnd_get');
	Route::post('/comment/upload_img', 'ReviewsFrontendController@uploadImage')->name('comment.upload_img.frontEnd');
	Route::get('/comment/upload_img', 'ReviewsFrontendController@uploadImage')->name('comment.upload_img.frontEnd_get');
	Route::post('/comment/delete_img', 'ReviewsFrontendController@deleteImage')->name('comment.delete_img.frontEnd');
	Route::post('/comment/like', 'ReviewsFrontendController@like')->name('comment.like.frontEnd');
	Route::post('/comment/unlike', 'ReviewsFrontendController@unlike')->name('comment.unlike.frontEnd');
	Route::post('/comment/get_summary_star_collection', 'ReviewsFrontendController@getSummaryStarCollection')->name('comment.getSummaryStarCollection');
	Route::get('/comment/get_summary_star_collection', 'ReviewsFrontendController@getSummaryStarCollection')->name('comment.getSummaryStarCollection_get');
});

Route::group(['prefix' => 'errors'], function() {
	Route::get('404', 'ErrorController@error404')->name('apps.errors404');
});
Route::group([], function() {
	Route::post('appUninstalled', 'WebHookController@uninstallApp')->name('webhook.uninstall_app');
	Route::post('deleteProduct', 'WebHookController@deleteProduct')->name('webhook.delete_product');
	Route::post('createdProduct', 'WebHookController@createdProduct')->name('webhook.create_product');
	Route::post('updatedProduct', 'WebHookController@updatedProduct')->name('webhook.update_product');
	Route::post('themesUpdate', 'WebHookController@themesUpdate')->name('webhook.themes_update');
	Route::post('themesPublish', 'WebHookController@themesPublish')->name('webhook.themes_publish');
    Route::post('customers/redact', 'WebHookController@customersRedact')->name('webhook.customers_redact');
	Route::post('shop/redact', 'WebHookController@shopRedact')->name('webhook.shop_redact');
	Route::post('manage-reviews/add_google_rating', 'ReviewsBackendController@addGoogleRating')->name('reviews.add_google_rating');
});

Route::group(['prefix' => 'cs'], function($router)
{
	$router->get('critical-update', 'AppsController@criticalUpdateApp');
	$router->post('critical-update', 'AppsController@criticalUpdateAppHandle')->name('critical.update.handle');
});
