<?php


namespace NovemBit\i18n\component\db;


use Doctrine\DBAL\Logging\SQLLogger;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class SQLFileLogger implements SQLLogger
{

    private $_logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }


    public function startQuery($sql, array $params = null, array $types = null)
    {
        $log = json_encode(['sql' => $sql, 'params' => $params, 'types' => $types]);
        $this->getLogger()->debug($log, [static::class]);
    }

    public function stopQuery()
    {
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->_logger;
    }
}
