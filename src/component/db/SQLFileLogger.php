<?php

namespace NovemBit\i18n\component\db;

use Doctrine\DBAL\Logging\DebugStack;
use Psr\Log\LoggerInterface;

class SQLFileLogger extends DebugStack
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $sql
     * @param array|null $params
     * @param array|null $types
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null): void
    {
        parent::startQuery($sql, $params, $types);

        $this->getLogger()->debug(json_encode($this->queries), [self::class]);
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
