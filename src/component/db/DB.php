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
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use NovemBit\i18n\Module;

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
class DB
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
    private array $connection_params;

    /**
     * Doctrine DB connection
     *
     * @see https://www.doctrine-project.org/projects/dbal.html
     * @var Connection
     * */
    private Connection $connection;


    /**
     * Get connection of DB
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if ( ! isset($this->connection)) {
            $config = new Configuration();

            $this->connection =
                DriverManager::getConnection(
                    $this->connection_params,
                    $config
                );
        }


        return $this->connection;
    }

    /**
     * @param  array  $connection_params
     *
     * @return DB
     */
    public function setConnectionParams(array $connection_params): DB
    {
        $this->connection_params = $connection_params;

        return $this;
    }
}
