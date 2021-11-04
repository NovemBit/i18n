<?php

/**
 * Main i18n module
 *
 * This php framework provides services to translate whole website without
 * Touching any structure of local project. This is only one request layer
 * That translates any http connection response. For example if response type
 * is `html` then module making translate whole DOM document with your custom
 * rules (Xpath and type mappers).
 * If you have xml, or json request then each type translating with custom
 * translation method.
 * All translations from remote API services saving to DB,
 * To make module faster and send less requests to DB we using PSR interface caches
 * (`memcached` or `redis`), or you can create your custom
 * PSR cache handler. Any component of module can have custom Cache pool and
 * custom handler, and each component can have custom PSR logger.
 *
 * php version 7.2.10
 *
 * @category Default
 * @package  Default
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n;

use NovemBit\i18n\component\db\DB;
use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\request\Request;
use NovemBit\i18n\component\rest\Rest;
use NovemBit\i18n\component\translation\Translation;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Module class
 * Main instance of i18n library. Should be used for any external connection
 * Provides component system. There have some required components,
 * DBAL (RDMS) configurations, Request handlers, Translation abstraction layer,
 *
 * @example ```php
 *              // Simple usage example for module instance
 *              // to use translation sub-component
 *              Module::instance()->translation->setLanguages(['ru','fr'])->text->translate(['hello'])
 *          ```
 *
 * @example ```php
 *              // Example to start request translation
 *              Module::instance()->request->start()
 *          ```
 *
 * @category Default
 * @package  Default
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class Module
{
    private static self $instance;

    /**
     * Prefix for any script public action
     * For example in translated HTML document attributes
     * JS global variables
     * e.t.c.
     *
     * @var string
     * */
    public string $prefix = 'i18n';

    /**
     * @var bool
     * */
    public bool $ssl = false;

    public function __construct(
        private Localization $localization,
        private Translation $translation,
        private Request $request,
//        private Rest $rest,
        private DB $db,
        private CacheInterface $cache_item_pool
    ) {
    }

    /**
     * Main start method of module
     * * Starting request component
     * * Starting rest component
     *
     * @return void
     */
    public function start(): void
    {
        //$this->rest->start();
        $this->request->start();
    }

    /**
     * @return bool
     */
    public function isSsl(): bool
    {
        return $this->ssl;
    }

    public function setSsl(bool $ssl): self
    {
        $this->ssl = $ssl;

        return $this;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCacheItemPool(): CacheItemPoolInterface
    {
        return $this->cache_item_pool;
    }
}
