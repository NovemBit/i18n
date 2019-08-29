<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {

	include_once "vendor/autoload.php";

	spl_autoload_register( function ( $className ) {

		if (strpos($className, "NovemBit\i18n") === 0) {

			$className = str_replace("NovemBit\i18n",'src',$className);

			$className = str_replace( "\\", DIRECTORY_SEPARATOR, $className );

			include_once( __DIR__ . "/$className.php" );
		}

	} );

} catch ( Exception $e ) {
	throw new Exception( 'Cannot include i18n php file.' );
}

if(!defined('NOVEMBIT_I18N_CONFIG')){
    define('NOVEMBIT_I18N_CONFIG',[

        'translation' => [
            'class'  => NovemBit\i18n\component\Translation::class,
            'method' => [
                'class'      => NovemBit\i18n\component\translation\method\Dummy::class,
                'exclusions' => ['barev', 'barev duxov', "hayer",'Hello'],
                'validation' => true,
                'save_translations' => true
            ],
            'text'   => [
                'class' => NovemBit\i18n\component\translation\type\Text::class,
                'save_translations' => true,
//                'exclusions' => [ "Hello"],
                'validation' => false,
            ],
            'url'    => [
                'class' => NovemBit\i18n\component\translation\type\URL::class,
                'path_exclusion_patterns' => [
                    '.*\.php',
                    '^wp-admin(\/|$)',
                ],
                'url_validation_rules' => [
                    'host' => [
                        '^$|^swanson\.co\.uk$|^wp\.me$',
                    ]
                ]
            ],
            'html'   => [
                'class'             => NovemBit\i18n\component\translation\type\HTML::class,
                'save_translations' => false
            ]
        ],
        'languages'   => [
            'class'            => NovemBit\i18n\component\Languages::class,
            'accept_languages' => ['hy', 'fr', 'it', 'de', 'ru']
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

    ]);
}

$i18n = new NovemBit\i18n\Module(NOVEMBIT_I18N_CONFIG);