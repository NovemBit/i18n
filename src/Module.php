<?php


namespace NovemBit\i18n;

/**
 * @property  component\Translation translation
 * @property  component\Languages   languages
 * @property  component\DB          db
 */
class Module extends system\Component {

	public $config
		= [

			'translation' => [
				'class'  => component\Translation::class,
				'method' => [
					'class'      => component\translation\method\Dummy::class,
					'exclusions' => [ 'barev', 'barev duxov', "hayer" ],
				],
				'text'   => [
					'class' => component\translation\type\Text::class
				],
				'url'    => [
					'class' => component\translation\type\URL::class
				],
				'html'   => [
					'class'         => component\translation\type\HTML::class,
					'alias_domains' => [ 'test.com' ],
                    'save_translations' => false
				]
			],
			'languages'   => [
				'class'            => component\Languages::class,
				'accept_languages' => [ 'hy', 'fr', 'it', 'de', 'ru' ]
			],
            'request'=>[
                'class'=>component\Request::class
            ],
			'db'          => [
				'class' => system\components\DB::class,
				'pdo'   => 'sqlite:../runtime/db/BLi18n.db',
				/*'pdo'      => 'mysql:host=localhost;dbname=swanson',
				'username' => "root",
				'password' => "Novem9bit",
				'config'   => [
					\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
				]*/
			]

		];


	/**
	 *
	 * @throws \Exception
	 */
	public function init() {

	}

}