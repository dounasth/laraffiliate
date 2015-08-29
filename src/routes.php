<?php


Route::group(array('before' => 'auth|auth.admin|init.admin', 'after' => '', 'prefix' => 'admin'), function() {

    Route::get('merchant', array('as' => 'merchant.list', 'uses' => 'LaraffiliateMerchantController@listAll'));
    Route::get('merchant/update/{id?}', array('as' => 'merchant.update', 'uses' => 'LaraffiliateMerchantController@update'));
    Route::any('merchant/save/{id?}', array('as' => 'merchant.save', 'uses' => 'LaraffiliateMerchantController@save'));
    Route::get('merchant/delete/{id}', array('as' => 'merchant.delete', 'uses' => 'LaraffiliateMerchantController@delete'));
    Route::get('merchant/trashed', array('as' => 'merchant.trashed', 'uses' => 'LaraffiliateMerchantController@listTrashed'));

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
    Route::get('merchant/feed/parse/{id}/{run?}', array('as' => 'feed.parse', 'uses' => 'LaraffiliateFeedController@parseFeed'));

    Route::get('feed_format', array('as' => 'feed_format.list', 'uses' => 'LaraffiliateFeedFormatController@listAll'));
    Route::get('feed_format/update/{id?}', array('as' => 'feed_format.update', 'uses' => 'LaraffiliateFeedFormatController@update'));
    Route::any('feed_format/save/{id?}', array('as' => 'feed_format.save', 'uses' => 'LaraffiliateFeedFormatController@save'));
    Route::get('feed_format/delete/{id?}', array('as' => 'feed_format.delete', 'uses' => 'LaraffiliateFeedFormatController@delete'));

    Route::post('ajax/commerce/categories/map-feed-cat/{feed_cat_id}/to/{cat_id}', array('as' => 'aff.feed.map-feed-cat', 'uses' => 'LaraffiliateFeedController@mapFeedCat'));

    Route::any('json/feed-fields/{id}', array('as' => 'json.feed-fields', 'uses' => 'LaraffiliateFeedController@jsonFeedFields'));
});
