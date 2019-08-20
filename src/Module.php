<?php


namespace NovemBit\i18n;

/**
 * @property  \i18n\component\translation\Component translation
 * @property  \i18n\component\languages\Component languages
 * @property  \i18n\component\db\Component db
 */
class Module extends system\Component {

	public $config = [

		'translation' => [
			'class'  => \i18n\component\translation\Component::class,
			'method' => [
				'class'      => \i18n\component\translation\component\method\Dummy::class,
				'exclusions' => [ 'barev', 'barev duxov', "hayer" ],
			],
			'text'   => [
				'class' => \i18n\component\translation\component\text\Component::class
			],
			'url'    => [
				'class' => \i18n\component\translation\component\url\Component::class
			],
			'html'   => [
				'class'         => \i18n\component\translation\component\html\Component::class,
				'alias_domains' => [ 'test.com' ]
			]
		],
		'languages'   => [
			'class'            => \i18n\component\languages\Component::class,
			'accept_languages' => [ 'hy', 'fr', 'it', 'de', 'ru' ]
		],
		'db'          => [
			'class'      => system\components\DB::class,
			'pdo' => 'sqlite:../runtime/db/BLi18n.db',
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