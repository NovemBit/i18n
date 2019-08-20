<?php

namespace NovemBit\i18n\system\components;

use NovemBit\i18n\system\Component;

class DB extends Component {

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

}