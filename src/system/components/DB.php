<?php

namespace NovemBit\i18n\system\components;

use NovemBit\i18n\system\Component;
use yii\db\Connection;

class DB extends Component
{

    public $pdo;
    public $username;
    public $password;
    public $config;

    private $_pdo;

    private $connection;

    /**
     *
     */
    public function init()
    {


        $this->_pdo = new \PDO($this->pdo, $this->username, $this->password, $this->config);
        $this->setConnection(new Connection([
            'dsn' => 'mysql:host=localhost;dbname=activerecord',
            'username' => 'top',
            'password' => 'top',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'i18n_',
//            'enableQueryCache' => true,
//            'enableSchemaCache' => true,
//            'schemaCacheDuration' => 3000,
//            'schemaCache' => 'cache',
        ]));
    }

    /**
     * @return \PDO
     */
    public function pdo()
    {
        return $this->_pdo;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

}