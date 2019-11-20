<?php


namespace NovemBit\i18n\component\cache;


use NovemBit\i18n\system\Component;
use Psr\SimpleCache\CacheInterface;

class Cache extends Component
{
    /**
     * PSR-6 interfaced cache class
     *
     * @var CacheInterface
     * */
    public $pool;

    /**
     * Get pool
     *
     * @return CacheInterface
     */
    public function getPool(): CacheInterface
    {
        return $this->pool;
    }

}