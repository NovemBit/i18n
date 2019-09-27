<?php


namespace NovemBit\i18n\component\translation\method;

/*
 * Dummy method of translation
 * That returns {lang}-{text} as translation
 * */

use Exception;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\TranslateClient;

class Google extends Method
{

    public $api_key;

    public $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * @throws Exception
     */
    public function init()
    {
        if (!isset($this->api_key)) {
            throw new Exception('Missing Google Cloud Translate API key.');
        }
    }

    /**
     * @param array $texts
     *
     * @return array
     * @throws Exception
     */
    protected function doTranslate(array $texts)
    {

        $languages = $this->context->getLanguages();

        $result = [];

        foreach ($languages as $language) {
            if($this->context->getFromLanguage() == $language){
                foreach ($texts as $text){
                    $result[$text][$language] = $text;
                    continue;
                }
            }

            $this->translateOneLanguage($texts, $language, $result);
        }

        return $result;
    }


    /**
     * @param array $texts
     * @param $to
     * @param $result
     * @return array|bool
     */
    public function translateOneLanguage(array $texts, $to, &$result)
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
                $translations = array_merge($translations, $gt_client->translateBatch($chunk));
            }
        } catch (GoogleException $e) {
            return false;
        }

        foreach ($translations as $key => $item) {
            $node = str_replace(array('<span translate="no">', '</span>'), '', $item['text']);
            if (!empty($node)) {
                $result[$item['input']][$to] = $node;
            }
        }
        return true;
    }
}