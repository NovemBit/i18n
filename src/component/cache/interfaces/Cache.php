<?php


namespace NovemBit\i18n\component\cache\interfaces;


use Psr\SimpleCache\CacheInterface;

interface Cache
{

    public function getPool(): CacheInterface;

}