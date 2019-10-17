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

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\TranslateClient;
use NovemBit\i18n\component\languages\exceptions\LanguageException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\method\exceptions\MethodException;
use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\component\translation\Translation;

/**
 * Google Translate method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Google extends Method
{
    /**
     * Google console api key for GT
     *
     * @var string
     * */
    public $api_key;

    /**
     * {@inheritdoc}
     * */
    public $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * {@inheritdoc}
     *
     * @return void
     * @throws MethodException
     */
    public function init()
    {
        if (!isset($this->api_key)) {
            throw new MethodException('Missing Google Cloud Translate API key.');
        }
    }

    /**
     * Doing translate method
     *
     * @param array $texts Array of texts to translate
     *
     * @return array
     * @throws LanguageException
     * @throws TranslationException
     */
    public function doTranslate(array $texts)
    {

        $languages = $this->context->getLanguages();

        $result = [];

        foreach ($languages as $language) {
            if ($this->context->getFromLanguage() == $language) {
                foreach ($texts as $text) {
                    $result[$text][$language] = $text;
                }
                continue;
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
     * @throws LanguageException
     * @return void
     */
    private function _translateOneLanguage(array $texts, $to, &$result) : void
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
        $translations = [];

        try {
            // todo: count the character
            $chunks = array_chunk($texts, 100);
            foreach ($chunks as $chunk) {
                $translations = array_merge(
                    $translations,
                    $gt_client->translateBatch($chunk)
                );
            }
        } catch (GoogleException $e) {
            /*
             * TODO: Make logger to log errors of GT
             * */
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
    }
}