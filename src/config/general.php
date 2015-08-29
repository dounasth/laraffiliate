<?php

return array(
    'path' => dirname(__FILE__).'/..',
    'import_db' =>
//    array(
//        'driver'   => 'sqlite',
//        'database' => __DIR__.'/../databases/import.sqlite',
//        'prefix'   => '',
//    ),
        array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'laraport_importdb',
            'username'  => 'laraport_importe',
            'password'  => '.(4mgs3q6.mW',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
);
