<?php
/**
 * Translation component
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

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
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
 *
 * @property Module $context
 */
class DB extends Component
{

    /**
     * Array of configuration for Yii2 DB connection
     *
     * @var array
     * */
    public $connection_params;

    /**
     * Yii2 DB connection
     *
     * @var Connection
     * */
    private $_connection;

    /**
     * {@inheritdoc}
     * Init method of component.
     * Setting default connection of DB
     *
     * @return void
     * @throws DBALException
     */
    public function commonInit(): void
    {

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => 'swanson',
            'user' => 'top',
            'password' => 'top',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',

        );

        $this->_setConnection(
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
        return $this->_connection;
    }

    /**
     * Set connection of DB
     *
     * @param Connection $_connection Yii2 db connection
     *
     * @return void
     */
    private function _setConnection(Connection $_connection): void
    {
        $this->_connection = $_connection;
    }

    /**
     * @return array
     */
    public function getConnectionParams(): array
    {
        return $this->connection_params;
    }
}