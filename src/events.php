<?php

Event::listen('admin.top-left-menu', function(){
    if ( Route::getCurrentRoute()->getPrefix() == 'admin' ) {
    $menu['merchants'] = array(
        'label' => 'Merchants',
        'href' => route('merchant.list'),
        'icon' => 'fa-list-ul',
    );
    $menu['feeds'] = array(
        'label' => 'Feeds',
        'href' => route('feed.list'),
        'icon' => 'fa-list-ul',
    );
    $menu['dividerl1'] = 'divider';

    $menu['formats'] = array(
        'label' => 'Feed Formats',
        'href' => route('feed_format.list'),
        'icon' => 'fa-list-ul',
    );
    $menu['dividerl3'] = 'divider';
    $menu['utilities'] = array(
        'label' => 'Utilities',
        'href' => '#',
        'icon' => 'fa-cogs',
        'submenu' => array(
            array(
                'label' => 'Linkwise Merchants list',
                'href' => route('utilities.linkwise.merchants'),
                'icon' => 'fa-list-ul',
            ),
            array(
                'label' => 'Skroutz Categories',
                'href' => route('utilities.skroutz-categories'),
                'icon' => 'fa-list-ul',
            ),
            array(
                'label' => 'Google Categories',
                'href' => route('utilities.google-categories'),
                'icon' => 'fa-list-ul',
            ),
        )
    );
    $menu['settings'] = array(
        'label' => 'Settings',
        'href' => '#',
        'icon' => 'fa-cogs',
    );
    return array(
        'affiliate-marketing' => array(
            'label' => 'Affiliate Marketing',
            'href' => '#',
            'icon' => 'fa-file-text-o',
            'submenu' => $menu
        ),
    );
    }
    else return [];
}, 999970);


Event::listen('laraffiliate.parsers', function(){
    return ['Bonweb\Laraffiliate\Parsers\LinkwiseToLaracartProductParser' => 'Linkwise To Laracart Products'];
}, 1000000);

Event::listen('laraffiliate.target-models', function(){
    return ['Bonweb\Laracart\Category' => 'Laracart Categories'];
}, 1000000);

\Bonweb\Laracart\Category::deleting(function($obj){
    if ($obj->trashed()) {
        return false;
    }
    return true;
});

Event::listen('admin.dashboard.widgets', function() {
    if ( Route::getCurrentRoute()->getPrefix() == 'admin' ) {
        $widget = new ViewWidget('cron-product-parser-stats', Widget::TYPE_VIEW);
        $widget->view = 'laraffiliate::widgets.cron-product-parser-stats';
        $widget->wrapClass = 'col-lg-6 col-xs-12 col-md-12';
        $widget->data = array();
        return $widget;
    }
    else return [];
}, 1000000);