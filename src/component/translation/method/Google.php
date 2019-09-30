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

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\TranslateClient;
use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\component\Translation;

/**
 * Google Translate method of translation
 *
 * @category Class
 * @package  Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Google extends Method
{

    public $api_key;

    public $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * {@inheritdoc}
     *
     * @return void
     * @throws Exception
     */
    public function init()
    {
        if (!isset($this->api_key)) {
            throw new Exception('Missing Google Cloud Translate API key.');
        }
    }

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

        $languages = $this->context->getLanguages();

        $result = [];

        foreach ($languages as $language) {
            if ($this->context->getFromLanguage() == $language) {
                foreach ($texts as $text) {
                    $result[$text][$language] = $text;
                    continue;
                }
            }

            $this->_translateOneLanguage($texts, $language, $result);
        }

        return $result;
    }


    /**
     * Translate texts to only one language
     *
     * @param array  $texts  Array of translatable texts
     * @param string $to     Language code
     * @param array  $result Referenced variable of results
     *
     * @return array|bool
     * @throws Exception
     */
    private function _translateOneLanguage(array $texts, $to, &$result)
    {
        $source = $this->context->getFromLanguage();

        $request_data = array(
            'key' => $this->api_key,
            'source' => $source,
            'target' => $to
        );
        /*if( ! empty( $credentials[ 'referer' ] ) ){
            $request_data['restOptions'] = array(
                'headers' => array(
                    'referer' => $credentials[ 'referer' ]
                )
            );
        }*/
        $gt_client = new  TranslateClient($request_data);

        try {
            // todo: count the character
            $chunks = array_chunk($texts, 100);
            $translations = array();
            foreach ($chunks as $chunk) {
                $translations = array_merge(
                    $translations,
                    $gt_client->translateBatch($chunk)
                );
            }
        } catch (GoogleException $e) {
            return false;
        }

        foreach ($translations as $key => $item) {
            $node = str_replace(
                array('<span translate="no">', '</span>'),
                '',
                $item['text']
            );

            if (!empty($node)) {
                $result[$item['input']][$to] = $node;
            }
        }
        return true;
    }
}