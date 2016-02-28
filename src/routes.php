<?php


Route::group(array('before' => 'auth|auth.admin|init.admin', 'after' => '', 'prefix' => 'admin'), function() {

    Route::get('merchant', array('as' => 'merchant.list', 'uses' => 'LaraffiliateMerchantController@listAll'));
    Route::get('merchant/update/{id?}', array('as' => 'merchant.update', 'uses' => 'LaraffiliateMerchantController@update'));
    Route::any('merchant/save/{id?}', array('as' => 'merchant.save', 'uses' => 'LaraffiliateMerchantController@save'));
    Route::get('merchant/delete/{id}', array('as' => 'merchant.delete', 'uses' => 'LaraffiliateMerchantController@delete'));
    Route::get('merchant/trashed', array('as' => 'merchant.trashed', 'uses' => 'LaraffiliateMerchantController@listTrashed'));
    Route::get('merchant/restore/{id}', array('as' => 'merchant.restore', 'uses' => 'LaraffiliateMerchantController@restoreTrashed'));

    Route::get('merchant/feed', array('as' => 'feed.list', 'uses' => 'LaraffiliateFeedController@listAll'));
    Route::get('merchant/feed/update/{id?}', array('as' => 'feed.update', 'uses' => 'LaraffiliateFeedController@update'));
    Route::any('merchant/feed/save/{id?}', array('as' => 'feed.save', 'uses' => 'LaraffiliateFeedController@save'));
    Route::get('merchant/feed/delete/{id}', array('as' => 'feed.delete', 'uses' => 'LaraffiliateFeedController@delete'));
    Route::get('merchant/feed/trashed', array('as' => 'feed.trashed', 'uses' => 'LaraffiliateFeedController@listTrashed'));

    Route::get('merchant/feed/refresh_sample/{id}', array('as' => 'feed.refresh_sample', 'uses' => 'LaraffiliateFeedController@refreshSample'));
    Route::get('merchant/feed/map-fields/{feed_id}', array('as' => 'feed.map-fields', 'uses' => 'LaraffiliateFeedController@mapFields'));
    Route::post('merchant/feed/save-map-fields/{feed_id}', array('as' => 'feed.map-fields.save', 'uses' => 'LaraffiliateFeedController@saveMapFields'));
    Route::get('merchant/feed/map_categories/{id}', array('as' => 'feed.map_categories', 'uses' => 'LaraffiliateFeedController@mapCategories'));
    Route::get('merchant/feed/products-sample/feed/{feed_id}/category/{feed_category}', array('as' => 'feed.products-sample', 'uses' => 'LaraffiliateFeedController@productsSample'));
    Route::get('merchant/feed/add-category/{feed_id}/{feed_category}', array('as' => 'feed.add-category', 'uses' => 'LaraffiliateFeedController@addCategory'));
    Route::get('merchant/feed/save-pos-category', array('as' => 'feed.save-pos-category', 'uses' => 'LaraffiliateFeedController@savePositionedCategory'));
    Route::get('merchant/feed/parse/{id}/{run?}', array('as' => 'feed.parse', 'uses' => 'LaraffiliateFeedController@parseFeed'));

    Route::get('feed_format', array('as' => 'feed_format.list', 'uses' => 'LaraffiliateFeedFormatController@listAll'));
    Route::get('feed_format/update/{id?}', array('as' => 'feed_format.update', 'uses' => 'LaraffiliateFeedFormatController@update'));
    Route::any('feed_format/save/{id?}', array('as' => 'feed_format.save', 'uses' => 'LaraffiliateFeedFormatController@save'));
    Route::get('feed_format/delete/{id?}', array('as' => 'feed_format.delete', 'uses' => 'LaraffiliateFeedFormatController@delete'));

    Route::get('utilities/linkwise/merchants', array('as' => 'utilities.linkwise.merchants', 'uses' => 'LaraffiliateUtilitiesController@linkwiseMerchants'));
    Route::get('utilities/linkwise/merchants/add/{mid}', array('as' => 'utilities.linkwise.merchants.add', 'uses' => 'LaraffiliateUtilitiesController@linkiseMerchantAdd'));
    Route::get('utilities/skroutz-categories', array('as' => 'utilities.skroutz-categories', 'uses' => 'LaraffiliateUtilitiesController@skroutzCategories'));
    Route::get('utilities/skroutz-categories/import/{parent_id}', array('as' => 'utilities.skroutz-categories.import', 'uses' => 'LaraffiliateUtilitiesController@skroutzCategoriesImport'));
    Route::get('utilities/google-categories', array('as' => 'utilities.google-categories', 'uses' => 'LaraffiliateUtilitiesController@googleCategories'));
    Route::get('utilities/google-categories/import/{parent_id}', array('as' => 'utilities.google-categories.import', 'uses' => 'LaraffiliateUtilitiesController@googleCategoriesImport'));

    Route::post('ajax/commerce/categories/map-feed-cat/{feed_cat_id}/to/{cat_id}', array('as' => 'aff.feed.map-feed-cat', 'uses' => 'LaraffiliateFeedController@mapFeedCat'));
    Route::post('ajax/commerce/categories/unmap-feed-cat/{feed_cat_id}', array('as' => 'aff.feed.unmap-feed-cat', 'uses' => 'LaraffiliateFeedController@unmapFeedCat'));

    Route::any('json/feed-fields/{id}', array('as' => 'json.feed-fields', 'uses' => 'LaraffiliateFeedController@jsonFeedFields'));

    Route::get('cron:parse-products', function(){
        Artisan::call('cron:parse-products');
        exit;
    });

});

Route::group(array('before' => '', 'after' => 'cache'), function() {
    Route::any('/coupon/{id}', array('as' => 'site.coupons.one', 'uses' => 'LaraffiliateSiteController@coupon'));
    Route::any('/coupons', array('as' => 'site.coupons.list', 'uses' => 'LaraffiliateSiteController@coupons'));
});