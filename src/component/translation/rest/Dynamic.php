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


namespace NovemBit\i18n\component\translation\rest;


use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\method\Method;
use NovemBit\i18n\component\translation\Translation;
use NovemBit\i18n\component\translation\Translator;
use NovemBit\i18n\system\helpers\URL;
use \NovemBit\i18n\component\translation\interfaces;

/**
 * Rest Translate method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Dynamic extends Translator implements interfaces\Rest
{
    /**
     * Type of translation.
     * Same as Method::name or JSON::name
     *
     * @example json
     * @example html
     * @example url
     * @example text
     * @example method
     *
     * @var string
     * */
    public $type = Method::NAME;

    /**
     * Version of REST API
     *
     * @var string
     * */
    public $api_version = "1";

    /**
     * Use SSL protocol
     *
     * @var bool
     * */
    public $ssl = false;

    /**
     * Rest api remote host
     *
     * @var string
     * */
    public $remote_host;

    /**
     * Remote path of API
     *
     * @var string
     * @see Rest::$endpoint
     * */
    public $remote_path = 'i18n/rest/v1';

    /**
     * Key of Remote REST api service
     *
     * @var string
     * @see \NovemBit\i18n\component\rest\Rest::$api_keys
     * */
    public $api_key;

    /**
     * Doing translate method
     *
     * @param array $texts Array of texts to translate
     *
     * @return array
     * @throws TranslationException
     */
    public function doTranslate(array $texts): array
    {

        $url = URL::buildUrl(
            [
                'scheme' => $this->ssl ? "https" : "http",
                'host' => $this->remote_host,
                'path' => $this->remote_path . "/translate",
                'query' => 'api_key=' . $this->api_key
            ]
        );

        $ch = curl_init($url);

        $data = http_build_query(
            [
                'from_language' => $this->context->context->languages->from_language,
                'languages' => $this->context->getLanguages(),
                'type' => $this->getType(),
                'texts' => $texts
            ]
        );

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($result, true);

        if ($result['status'] == 1) {
            return $result['translation'];
        } else {
            /**
             * Throw exception
             *
             * @todo Split errors
             * */
            throw new TranslationException(
                "Dynamic hub: response unexpected error."
            );
        }

    }

    /**
     * Get type of current translation
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}