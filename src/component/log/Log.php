<?php


namespace NovemBit\i18n\component\log;

use NovemBit\i18n\system\Component;
use Psr\Log\LoggerInterface;

class Log extends Component implements interfaces\Log
{

    /**
     *
     * @var LoggerInterface
     * */
    public $logger;


    public function logger(): LoggerInterface
    {

        return $this->logger;
    }

}