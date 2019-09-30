<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Translation
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */


namespace NovemBit\i18n\component\translation\method;


use Exception;
use NovemBit\i18n\component\Translation;
use NovemBit\i18n\system\helpers\URL;

/**
 * Rest Translate method of translation
 *
 * @category Class
 * @package  Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class RestMethod extends Method
{
    public $ssl = false;

    public $remote_host;

    public $remote_path = 'i18n/rest/v1';

    public $api_key;

    /**
     * Doing translate method
     *
     * @param array $texts Array of texts to translate
     *
     * @return array
     * @throws Exception
     */
    protected function doTranslate(array $texts)
    {

        /* API URL */
        $url = URL::buildUrl(
            [
                'scheme' => $this->ssl ? "https" : "http",
                'host' => $this->remote_host,
                'path' => $this->remote_path . "/translate",
                'query' => 'api_key=' . $this->api_key
            ]
        );

        /* Init cURL resource */
        $ch = curl_init($url);

        /* Array Parameter Data */
        $data = http_build_query(
            [
                'languages' => $this->context->getLanguages(),
                'texts' => $texts
            ]
        );

        //        var_dump($data);
        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute request */
        $result = curl_exec($ch);

        /* close cURL resource */
        curl_close($ch);

        $result = json_decode($result, true);

        return $result;

    }

}