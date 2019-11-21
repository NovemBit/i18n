<?php
/**
 * Main i18n module
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


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use NovemBit\i18n\component\cache\interfaces\Cache;
use NovemBit\i18n\component\languages\interfaces\Languages;
use NovemBit\i18n\component\log\interfaces\Log;
use NovemBit\i18n\component\request\interfaces\Request;
use NovemBit\i18n\component\rest\interfaces\Rest;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\component\DB;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
/**
 * Module class
 *
 * @category Default
 * @package  Default
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Cache cache
 * @property Log log
 * @property Translation translation
 * @property Languages languages
 * @property Request request
 * @property Rest rest
 * @property system\component\DB db
 */
class Module extends system\Component
{

    /**
     * Main instance of Module
     *
     * @var Module
     * */
    private static $_instance;

    /**
     * Prefix for any public action
     * For example in translated HTML document attributes
     * JS global variables
     * e.t.c.
     *
     * @var string
     * */
    public $prefix = 'i18n';


    /**
     * Default component configuration
     *
     * @return array
     * @throws \Exception
     */
    public static function defaultConfig(): array
    {

        /**
         * Runtime directory
         **/
        $runtime_dir = dirname(__DIR__).'/runtime';

        $filesystemAdapter = new Local($runtime_dir);
        $filesystem        = new Filesystem($filesystemAdapter);
        $pool = new FilesystemCachePool($filesystem);

        $logger = new Logger('default');
        $log_file = $runtime_dir.'/default.log';
        $logger->pushHandler(
            new StreamHandler(
                $log_file,
                Logger::WARNING
            )
        );

        return [
            'languages'=>[
                'class' => component\languages\Languages::class,
            ],
            'translation'=>[
                'class' => component\translation\Translation::class,
            ],
            'request'=>[
                'class' => component\request\Request::class,
            ],
            'rest'=>[
                'class' => component\rest\Rest::class,
            ],
            'db'=>[
                'class' => DB::class,
            ],
            'cache'=>[
                'class'=> component\cache\Cache::class,
                'pool'=>$pool
            ],
            'log'=>[
                'class'=> component\log\Log::class,
                'logger'=>$logger
            ]
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function mainInit(): void
    {
        $this->log->logger()->debug(
            'Module initialized.',
            [self::class]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Load Yii framework container to use some libraries that not
     * Allowed to use standalone
     *
     * @return void
     */
    public function commonLateInit(): void
    {

        /*
         * Check if yii framework not initialized
         * Then include yii2 layer class
         * */
        if (!class_exists("Yii")) {
            defined('YII_DEBUG') or define('YII_DEBUG', false);
            defined('YII_ENV') or define('YII_ENV', 'prod');
            include __DIR__ . '/../../../yiisoft/yii2/Yii.php';

            /*new Application(
                [
                    'id' => 'i18n',
                    'basePath' => dirname(__DIR__),
                    'components' => [
                        'cache' => [
                            'class' => 'yii\caching\FileCache',
                        ],
                    ],
                ]
            );*/

        }
        parent::commonLateInit();
    }

    /**
     * Start request translation
     *
     * @return void
     */
    public function start()
    {
        $this->rest->start();
        $this->request->start();
    }

    /**
     * Creating module main instance
     *
     * @param null|array $config Main configuration array
     *
     * @return Module
     */
    public static function instance($config = null)
    {

        if (!isset(self::$_instance) && ($config != null)) {
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

}