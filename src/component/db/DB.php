<?php

/**
 * DB component
 *
 * Global DB Abstraction layer class
 * We using **Doctrine DBAL** to provides universal RDMS support
 *
 * php version 7.2.10
 *
 * @category System\Components
 * @package  System\Components
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\db;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Exception;
use Monolog\Logger;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use Doctrine\DBAL\Connection;

/**
 * DB component
 *
 * @category System\Components
 * @package  System\Components
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 * @property Module $context
 */
class DB extends Component
{

    /**
     * Array of configuration for Yii2 DB connection
     *
     * @example ```php
     *    [
     *      'dbname' => 'swanson',
     *      'user' => 'top',
     *      'password' => 'top',
     *      'host' => 'localhost',
     *      'driver' => 'pdo_mysql'
     *      ...
     *    ];
     * ```
     * @see     https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/configuration.html
     *
     * @var array
     * */
    public $connection_params;

    /**
     * Doctrine DB connection
     *
     * @see https://www.doctrine-project.org/projects/dbal.html
     * @var Connection
     * */
    private $connection;

    /**
     * {@inheritdoc}
     * Init method of component.
     * Setting default connection of DB
     *
     * @return void
     * @throws DBALException
     * @throws Exception
     */
    public function commonInit(): void
    {
        $config = new Configuration();

        /**
         * @todo Temporary disabled sql Logger
         * */
        /*$config->setSQLLogger(
            new SQLFileLogger($this->getLogger())
        );*/

        $this->setConnection(
            DriverManager::getConnection(
                $this->getConnectionParams(),
                $config
            )
        );
    }

    /**
     * Get connection of DB
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Set connection of DB
     *
     * @param Connection $_connection Yii2 db connection
     *
     * @return void
     */
    private function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * Connection params getter
     *
     * @return array
     */
    public function getConnectionParams(): array
    {
        return $this->connection_params;
    }
}
