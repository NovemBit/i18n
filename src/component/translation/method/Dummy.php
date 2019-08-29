<?php


namespace NovemBit\i18n\component\translation\method;

/*
 * Dummy method of translation
 * That returns {lang}-{text} as translation
 * */

use Exception;

class Dummy extends Method
{

    public $exclusion_pattern = '{e-$0-e}';

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

        foreach ($texts as $key => $text) {

            foreach ($languages as $language) {
                $result[(string)$text][$language] = $text.'-'.$language;
            }

        }

        return $result;
    }

}