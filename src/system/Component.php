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

use NovemBit\i18n\system\helpers\Arrays;

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
                $config,
                $default
            );
        }

        if ($this->_isCli()) {

            global $argv, $argc;

            $this->cliLateInit($argv, $argc);

            $this->_extractConfig();

            $this->cliInit($argv, $argc);

            if (isset($argv[1]) && $argv[1] == get_called_class()) {
                $this->cli($argv, $argc);
            }

        } else {
            $this->lateInit();

            $this->_extractConfig();

            $this->init();
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


    /**
     * Common init method running before
     * Initialization of child components
     *
     * @return void
     */
    public function lateInit(): void
    {
    }

    /**
     * Component init method
     * Non CLI
     * Running after child component initialization
     *
     * @return void
     * */
    public function init(): void
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