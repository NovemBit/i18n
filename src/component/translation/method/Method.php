<?php


namespace NovemBit\i18n\component\translation\method;

/*
 * Translation method class
 * Any method of translation must extends this class
 * */

use Exception;
use NovemBit\i18n\component\translation\models\Translation;

/**
 * @property  \NovemBit\i18n\component\Translation context
 */
abstract class Method extends \NovemBit\i18n\component\translation\Translation
{
    public $type = 1;

    public $save_translations = true;

    public $use_saved_translations = true;

    public $exclusions = [];

    public $exclusion_pattern = '<span translate="no">$0</span>';

    public $clean_whitespace = true;

    /**
     * Method constructor.
     *
     */
    public function init()
    {

        $this->initExclusions();
    }

    /**
     * Sort exclusions by priority
     */
    private function initExclusions()
    {
        usort($this->exclusions, function ($a, $b) {
            if (strpos($a, $b) !== false) {
                return false;
            } else {
                return true;
            }
        });
    }

    /**
     * @param        $string
     * @param string $pattern | example {e-$0-e}
     *
     * @return string|string[]|null
     */
    private function doExclusion($string, $pattern)
    {
        return preg_replace(
            array_map(function ($exclusion) {
                return '/(?<=\s|^)(' . preg_quote($exclusion) . ')(?=\s|$)/i';
            }, $this->exclusions),
            $pattern,
            $string
        );
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function prepareText(string $text)
    {

        $this->clarifyText($text);
        // Todo: clarify text
        $text = $this->doExclusion($text, $this->exclusion_pattern);

        return $text;
    }

    /**
     * @param string $string
     *
     */
    private function clarifyText(string & $string)
    {

        /*
         * Whitespace
         * */
        $string = preg_replace('/\s+/', ' ', $string);

        /*
         * First and last spaces
         * */
        $string = trim($string, ' ');

    }


}