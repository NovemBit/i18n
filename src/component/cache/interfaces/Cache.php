<?php


namespace NovemBit\i18n\component\cache\interfaces;


use NovemBit\i18n\system\interfaces\Component;
use Psr\SimpleCache\CacheInterface;

interface Cache extends Component
{

    public function getPool(): CacheInterface;

}