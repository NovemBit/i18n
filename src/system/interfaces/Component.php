<?php


namespace NovemBit\i18n\system\interfaces;


use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

interface Component
{
    public function getLogger(): LoggerInterface;

    public function setLogger(LoggerInterface $logger): void;

    public function getRuntimeDir(): string;

    public function setRuntimeDir(string $runtime_dir): void;

    public function setCachePool(CacheInterface $cache_pool): void;

    public function getCachePool(): CacheInterface;

    public static function defaultConfig(): array;

}