<?php

namespace i18n\component\db;


class Component extends \NovemBit\i18n\system\Component {

	public $pdo;
	public $username;
	public $password;
	public $config;

	private $_pdo;

	/**
	 *
	 */
	public function init() {

		$this->_pdo = new \PDO( $this->pdo, $this->username, $this->password, $this->config );
	}

	/**
	 * @return \PDO
	 */
	public function pdo(){
		return $this->_pdo;
	}

	public function cli( $argv, $argc ) {

		echo "works" . PHP_EOL;

	}

}