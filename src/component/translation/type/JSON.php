<?php

namespace NovemBit\i18n\component\translation\type;

use Exception;
use NovemBit\i18n\component\Translation;

/**
 * @property  Translation context
 */
class JSON extends Type
{
    public $type = 4;

    public $validation = true;

    private $objects = [];

    /**
     * @param array $jsons
     * @return array
     * @throws Exception
     */
    public function doTranslate(array $jsons)
    {
        $languages = $this->context->getLanguages();

        $to_translate = [];
        $translations = [];
        $result = [];
        foreach ($jsons as $json){
            $object = $this->objects[$json];
            array_walk_recursive($object, function (&$item, $key) use(&$to_translate){
                if(self::isHTML($item)){
                    $to_translate['html'][] = $item;
                }
            });
        }

        foreach ($to_translate as $type=>$values){
            $translations[$type] = $this->context->{$type}->translate($values);
        }


        foreach ($jsons as &$json){
            foreach($languages as $language){
                $object = $this->objects[$json];
                array_walk_recursive($object, function (&$item, $key) use($translations,$language) {
                    if(self::isHTML($item)){
                        $item = isset($translations['html'][$item][$language]) ? $translations['html'][$item][$language] :$item;
                    }
                });
                $result[$json][$language] =json_encode($object);
            }
        }

        return $result;

        /*$translations = $this->context->method->translate($texts);
        foreach ($translations as $source => &$translation) {
            foreach ($translation as $language => &$text) {
                $text = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML401);
            }
        }*/
        //return $translations;
    }

    public function validateBeforeTranslate(&$json)
    {
        $this->objects[$json] = json_decode($json,true);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private static function isHTML($string){
        return $string != strip_tags($string);
    }
}