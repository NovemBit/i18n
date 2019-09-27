<?php

namespace NovemBit\i18n\system;

/*  BLi18n
 *
 * Main system component class
 * That helps to build very flexible and beautiful
 * Structure of application
 * Like very popular frameworks
 *
 * Its simple but provides very useful functionality
 * */


abstract class Component
{

    public $config;

    public $context;

    /**
     * Component constructor.
     *
     * @param array $config
     * @param null $context
     *
     */
    public function __construct($config = [], & $context = null)
    {

        $this->context = $context;
        if (!isset($this->config)) {
            $this->config = $config;
        }

        $this->commonInit();

        foreach ($this->config as $key => $value) {
            if (is_array($value) && isset($value['class'])) {
                $sub_class = $value['class'];
                unset($value['class']);
                $this->{$key} = new $sub_class($value, $this);
            } else {
                $this->{$key} = $value;
            }
        }


        $this->init();

        if ($this->_is_cli()) {

            global $argv, $argc;

            $this->cliInit($argv, $argc);

            if (isset($argv[1]) && $argv[1] == get_called_class()) {
                $this->cli($argv, $argc);
            }

        }

    }


    /**
     *
     */
    public function commonInit()
    {

    }

    /**
     */
    public function run()
    {
    }

    /*
     * Component init method
     * Using as constructor
     * */
    public function init()
    {
    }

    /**
     * Action that will run
     * Only on cli script
     *
     * @param $argv
     * @param $argc
     */
    public function cli($argv, $argc)
    {
    }


    /**
     * Init only for CLI
     *
     * @param $argv
     * @param $argc
     */
    public function cliInit($argv, $argc)
    {

    }


    /**
     * @return bool
     */
    private function _is_cli()
    {
        return php_sapi_name() === 'cli';
    }

}