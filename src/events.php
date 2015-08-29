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
    $menu['dividerlc1'] = 'divider';
    $menu['formats'] = array(
        'label' => 'Feed Formats',
        'href' => route('feed_format.list'),
        'icon' => 'fa-list-ul',
    );
    $menu['dividerlc1'] = 'divider';
    $menu['settings'] = array(
        'label' => 'Settings',
        'href' => '#',
        'icon' => 'fa-cogs',
        'submenu' => array(
            array(
                'label' => '!@#',
                'href' => '#',
                'icon' => 'fa-list-ul',
            ),
            array(
                'label' => '$%^',
                'href' => '#',
                'icon' => 'fa-list-ul',
            ),
        )
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