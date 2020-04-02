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
use Exception;
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
abstract class Component implements interfaces\Component
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
     * Logger with PSR interface
     *
     * @var LoggerInterface
     * */
    public $logger;

    /**
     * Logging level
     *
     * @var int
     * */
    public $logging_level = Logger::WARNING;

    /**
     * Cache Pool with PSR interface
     *
     * @var CacheInterface
     * */
    public $cache_pool;

    /**
     * Runtime directory
     * Should be used as main caching and runtime temp files
     *
     * @var string
     * */
    public $runtime_dir = __DIR__ . '/../../runtime';

    /**
     * Component constructor.
     *
     * @param array          $config  Configuration array
     * @param null|Component $context Context (parent) Component
     */
    public function __construct($config = [], &$context = null)
    {
        $this->context = $context;

        if (!isset($this->config)) {
            $this->mergeAndSetConfig($config);
        }

        if ($this->isCli()) {
            global $argv, $argc;

            $this->commonLateInit();

            $this->cliLateInit($argv, $argc);

            $this->extractConfig();

            $this->commonInit();

            $this->cliInit($argv, $argc);

            if (isset($argv[1]) && $argv[1] == get_called_class()) {
                $this->cli($argv, $argc);
            }
        } else {
            $this->commonLateInit();

            $this->mainLateInit();

            $this->extractConfig();

            $this->commonInit();

            $this->mainInit();
        }
    }

    /**
     * Extract config array
     *
     * @return void
     */
    private function extractConfig(): void
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

    /**
     * @param array|null $config
     */
    private function mergeAndSetConfig(array $config = [])
    {
        $default = static::defaultConfig();

        $this->config = Arrays::arrayMergeRecursiveDistinct(
            $default,
            $config
        );
    }

    /**
     * @param array|null $config
     */
    public function reInit(?array $config = []): void
    {
        if ($config !== null) {
            $this->mergeAndSetConfig($config);
        }
        $this->extractConfig();
    }

    /**
     * Common late init method
     * Running first, before all other actions
     * And before sub components init
     *
     * @return void
     */
    protected function commonLateInit(): void
    {
    }

    /**
     * Common init method
     * Running after sub components init
     *
     * @return void
     */
    protected function commonInit(): void
    {
    }

    /**
     * Main init method running before
     * Initialization of child components
     *
     * @return void
     */
    protected function mainLateInit(): void
    {
    }

    /**
     * Main init method
     * Running after child component initialization
     *
     * @return void
     * */
    protected function mainInit(): void
    {
    }

    /**
     * Action that will run
     * Only on cli script
     *
     * @param array $argv Array of cli arguments
     * @param int   $argc Count of cli arguments
     *
     * @return void
     */
    protected function cli($argv, $argc): void
    {
    }


    /**
     * Init method only for CLI
     *
     * @param array $argv Array of cli arguments
     * @param int   $argc Count of cli arguments
     *
     * @return void
     */
    protected function cliInit($argv, $argc): void
    {
    }

    /**
     * Init method only for CLI
     *
     * @param array $argv Array of cli arguments
     * @param int   $argc Count of cli arguments
     *
     * @return void
     */
    protected function cliLateInit($argv, $argc): void
    {
    }

    /**
     * Check if script running on CLI
     *
     * @return bool
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Get current component logger
     *
     * @return LoggerInterface
     * @throws Exception
     */
    public function getLogger(): LoggerInterface
    {
        if (!isset($this->logger)) {
            /**
             * Runtime directory
             *
             * @see $runtime_dir
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
     * Set logger
     *
     * @param LoggerInterface $logger Logger with PSR interface
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Get runtime directory
     *
     * @return string
     */
    public function getRuntimeDir(): string
    {
        return $this->runtime_dir;
    }

    /**
     * Set runtime directory
     *
     * @param string $runtime_dir Runtime directory
     *
     * @return void
     */
    public function setRuntimeDir(string $runtime_dir): void
    {
        $this->runtime_dir = $runtime_dir;
    }

    /**
     * Setting cache pool with PSR interface
     *
     * @param CacheInterface $cache_pool Cache pool
     *
     * @return void
     */
    public function setCachePool(CacheInterface $cache_pool): void
    {
        $this->cache_pool = $cache_pool;
    }

    /**
     * Get cache pool
     *
     * @return CacheInterface
     */
    public function getCachePool(): CacheInterface
    {
        if (!isset($this->cache_pool)) {
            $path = trim(str_replace('\\', '/', static::class), '/');
            $cache_dir = $this->getRuntimeDir() . "/cache/" . $path;

            $filesystemAdapter = new Local($cache_dir);
            $filesystem = new Filesystem($filesystemAdapter);
            $this->setCachePool(new FilesystemCachePool($filesystem, 'cache'));
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

    /**
     * Default config for current component
     *
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [];
    }
}
