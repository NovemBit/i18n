<?php


namespace NovemBit\i18n;


/**
 * @property  component\Translation translation
 * @property  component\Languages languages
 * @property  component\Request request
 * @property  component\DB db
 * @property  component\Rest rest
 * @property  test\Test test
 */
class Module extends system\Component
{
    private static $instance;

    public $prefix = 'i18n';

    /**
     *
     * @throws \Exception
     */
    public function init()
    {

    }

    public function start(){
        $this->rest->start();
        $this->request->start();
    }

    /**
     * @param null $config
     * @return Module
     */
    public static function instance($config = null){

        if(!isset(self::$instance) && $config!=null){
            self::$instance = new self($config);
        }

        return self::$instance;
    }

}