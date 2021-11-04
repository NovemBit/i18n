<?php

/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\method;

use Exception;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\rest\interfaces\Rest as RestComponentInterface;
use NovemBit\i18n\system\helpers\URL;
use NovemBit\i18n\component\translation\interfaces;

/**
 * Rest Translate method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 * @deprecated
 */
class Rest extends Method
{

    /**
     * Version of REST API
     *
     * @var string
     * */
    public string $api_version = '1';

    /**
     * Use SSL protocol
     *
     * @var bool
     * */
    public bool $ssl = false;

    /**
     * Rest api remote host
     *
     * @var string
     * */
    public string $remote_host;

    /**
     * Remote path of API
     *
     * @var string
     * @see Rest::$endpoint
     * */
    public string $remote_path = 'i18n/rest/v1';

    /**
     * Key of Remote REST api service
     *
     * @var string
     * @see \NovemBit\i18n\component\rest\Rest::$api_keys
     * */
    public string $api_key;

    /**
     * Timeout of curl request
     *
     * @var int
     * */
    public int $request_timeout = 4;

    /**
     * Doing translate method
     *
     * @param array $nodes Array of texts to translate
     * @param string $from_language
     * @param array $to_languages
     * @param bool $ignore_cache
     *
     * @return array
     * @throws TranslationException
     * @throws Exception
     */
    protected function doTranslate(
        array $nodes,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        $url = URL::buildUrl(
            [
                'scheme' => $this->ssl ? 'https' : 'http',
                'host' => $this->remote_host,
                'path' => $this->remote_path . '/translate',
                'query' => 'api_key=' . $this->api_key
            ]
        );

        $ch = curl_init($url);

        $query = [
            'languages' => $to_languages,
            'localization_config' => $this->getLocalizationConfig(),
            'texts' => $nodes,
        ];
        

        $data = http_build_query(
            $query
        );

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->request_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, ($this->request_timeout + 7));
        $result = curl_exec($ch);

        if ($result === false) {

            /**
             * Error reporting for dynamic hub
             * */
            $this->getLogger()->info(
                'NovemBit i18n rest endpoint: not responding.'
            );

            return [];
        }

        curl_close($ch);

        $result = json_decode($result, true);

        $status = $result['status'] ?? RestComponentInterface::STATUS_NONE;

        $translation = [];

        if ($status === RestComponentInterface::STATUS_DONE) {
            $translation = $result['translation'] ?? [];
        } elseif ($status === RestComponentInterface::STATUS_ERROR) {
            $this->getLogger()->warning(
                $result['message'] ?? 'Rest endpoint: unexpected error.'
            );
        } elseif ($status === RestComponentInterface::STATUS_EMPTY) {
            $this->getLogger()->warning(
                $result['message'] ?? 'Rest endpoint: empty response.'
            );
        } else {
            $this->getLogger()->warning(
                'Rest endpoint: negative response.'
            );
        }
        return $translation;
    }

    /**
     * Get languages configuration from main module instance `$config`
     *
     * @return array
     * @throws TranslationException
     */
    public function getLocalizationConfig(): array
    {
        $config = $this->context->context->localization->config ?? null;

        if ($config === null) {
            throw new TranslationException('Localization config not found.');
        }

        /**
         * Unset runtime and class props
         * */
        unset(
            $config['runtime_dir'],
            $config['languages']['class'],
            $config['languages']['runtime_dir'],
            $config['languages']['all'],
            $config['regions']['class'],
            $config['regions']['runtime_dir'],
            $config['regions']['all'],
            $config['countries']['class'],
            $config['countries']['runtime_dir'],
            $config['countries']['all']
        );

        return $config;
    }
}
