<?php
return [

    'translation' => [
        'class'  => NovemBit\i18n\component\Translation::class,
        'method' => [
            'class'      => NovemBit\i18n\component\translation\method\Google::class,
            'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
            'exclusions' => ['barev', 'barev duxov', "hayer",'Hello'],
            'validation' => true,
            'save_translations' => true
        ],
        'text'   => [
            'class' => NovemBit\i18n\component\translation\type\Text::class,
            'save_translations' => true,
//                'exclusions' => [ "Hello"],
        ],
        'url'    => [
            'class' => NovemBit\i18n\component\translation\type\URL::class,
            'url_validation_rules' => [
                'host' => [
                    '^$|^swanson\.co\.uk$|^wp\.me$',
                ]
            ]
        ],
        'html'   => [
            'class'             => NovemBit\i18n\component\translation\type\HTML::class,
            'save_translations' => false
        ],
        'json'   => [
            'class'             => NovemBit\i18n\component\translation\type\JSON::class,
            'save_translations' => false
        ]
    ],
    'languages'   => [
        'class'            => NovemBit\i18n\component\Languages::class,
        'accept_languages' => ['hy', 'fr', 'it', 'de', 'ru'],
        'path_exclusion_patterns' => [
            '.*\.php',
            '.*wp-admin',
        ],
    ],
    'request'     => [
        'class' => NovemBit\i18n\component\Request::class,
    ],
    'db'          => [
        'class' => NovemBit\i18n\system\components\DB::class,
        'pdo'   => 'sqlite:'.__DIR__.'/runtime/db/BLi18n.db',
        /*'pdo'      => 'mysql:host=localhost;dbname=swanson',
        'username' => "root",
        'password' => "Novem9bit",
        'config'   => [
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
        ]*/
    ]
];
