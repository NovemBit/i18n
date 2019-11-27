<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category System
 * @package  System
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\system;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use NovemBit\i18n\system\helpers\Arrays;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Main system inheritance component class
 * That helps to build very flexible and beautiful
 * Structure of application
 * Like very popular frameworks
 *
 * Its simple but provides very useful functionality
 * Module class
 *
 * @category System
 * @package  System
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 * */
abstract class Component
{

    /**
     * Constructor configuration array
     *
     * @var array
     * */
    public $config;

    /**
     * Context (parent) component of current component
     *
     * @var Component
     * */
    public $context;

    /**
     * PSR Logger
     *
     * @var LoggerInterface
     * */
    public $logger;

    /**
     * PSR logging level
     *
     * @var int
     * */
    public $logging_level = Logger::WARNING;

    /**
     * @var CacheInterface
     * */
    public $cache_pool;

    /**
     * @var string
     * */
    public $runtime_dir = __DIR__ . '/../../runtime';

    /**
     * Component constructor.
     *
     * @param array $config Configuration array
     * @param null|Component $context Context (parent) Component
     */
    public function __construct($config = [], & $context = null)
    {

        $this->context = $context;

        if (!isset($this->config)) {

            $default = static::defaultConfig();

            $this->config = Arrays::arrayMergeRecursiveDistinct(
                $default,
                $config
            );
        }

        if ($this->_isCli()) {

            global $argv, $argc;

            $this->commonLateInit();

            $this->cliLateInit($argv, $argc);

            $this->_extractConfig();

            $this->commonInit();

            $this->cliInit($argv, $argc);

            if (isset($argv[1]) && $argv[1] == get_called_class()) {
                $this->cli($argv, $argc);
            }

        } else {
            $this->commonLateInit();

            $this->mainLateInit();

            $this->_extractConfig();

            $this->commonInit();

            $this->mainInit();
        }

    }

    /**
     * Extract config array
     *
     * @return void
     */
    private function _extractConfig(): void
    {
        foreach ($this->config as $key => $value) {
            if (is_array($value) && isset($value['class'])) {
                $sub_class = $value['class'];
                unset($value['class']);
                $this->{$key} = new $sub_class($value, $this);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    public function commonLateInit(): void
    {

    }

    public function commonInit(): void
    {

    }

    /**
     * Common init method running before
     * Initialization of child components
     *
     * @return void
     */
    public function mainLateInit(): void
    {
    }

    /**
     * Component init method
     * Non CLI
     * Running after child component initialization
     *
     * @return void
     * */
    public function mainInit(): void
    {
    }

    /**
     * Action that will run
     * Only on cli script
     *
     * @param array $argv Array of cli arguments
     * @param int $argc Count of cli arguments
     *
     * @return void
     */
    public function cli($argv, $argc): void
    {
    }


    /**
     * Init method only for CLI
     *
     * @param array $argv Array of cli arguments
     * @param int $argc Count of cli arguments
     *
     * @return void
     */
    public function cliInit($argv, $argc): void
    {
    }

    /**
     * Init method only for CLI
     *
     * @param array $argv Array of cli arguments
     * @param int $argc Count of cli arguments
     *
     * @return void
     */
    public function cliLateInit($argv, $argc): void
    {
    }


    /**
     * Check if script running on CLI
     *
     * @return bool
     */
    private function _isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * @return LoggerInterface
     * @throws \Exception
     */
    public function getLogger(): LoggerInterface
    {
        if (!isset($this->logger)) {
            /**
             * Runtime directory
             **/
            $path = trim(str_replace('\\', '/', static::class), '/');
            $log_dir = $this->getRuntimeDir() . "/logs/" . $path;
            $log_file = $log_dir . '/' . date("Y-m-d") . '.log';
            $logger = new Logger('default');
            $logger->pushHandler(
                new StreamHandler(
                    $log_file,
                    $this->logging_level
                )
            );
            $this->setLogger($logger);
        }
        return $this->logger;
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getRuntimeDir(): string
    {
        return $this->runtime_dir;
    }

    /**
     * @param string $runtime_dir
     */
    public function setRuntimeDir(string $runtime_dir): void
    {
        $this->runtime_dir = $runtime_dir;
    }

    /**
     * @param CacheInterface $cache_pool
     */
    public function setCachePool(CacheInterface $cache_pool): void
    {
        $this->cache_pool = $cache_pool;
    }

    /**
     * @return CacheInterface
     */
    public function getCachePool(): CacheInterface
    {
        if (!isset($this->cache_pool)) {

            $path = trim(str_replace('\\', '/', static::class), '/');
            $cache_dir = $this->getRuntimeDir() . "/cache/" . $path;

            $filesystemAdapter = new Local($cache_dir);
            $filesystem = new Filesystem($filesystemAdapter);
            $this->setCachePool(new FilesystemCachePool($filesystem, ''));
        }

        return $this->cache_pool;
    }

    /**
     * Prevent cloning of instance
     *
     * @return void
     */
    private function __clone()
    {
    }

    public static function defaultConfig(): array
    {
        return [];
    }


}