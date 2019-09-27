<?php


namespace NovemBit\i18n\component\translation;


use Exception;
use NovemBit\i18n\system\Component;

/**
 * @property  \NovemBit\i18n\component\Translation context
 */
abstract class Translation extends Component
{
    public $type = 0;

    public $save_translations = true;

    public $use_saved_translations = true;

    public $validation = false;

    public $exclusions = [];

    public $exclusion_pattern = '<span translate="no">$0</span>';

    private $_translate_original_texts = [];

    private $_re_translate_original_texts = [];

    /**
     * Init
     */
    public function init()
    {
        $this->initExclusions();

    }

    /**
     * @param array $texts
     */
    public function beforeTranslate(array &$texts)
    {

        if ($this->validation == true) {
            $this->validateAllBeforeTranslate($texts);
        }

    }

    /**
     * @param array $translations
     */
    public function afterTranslate(array &$translations)
    {
        if ($this->validation == true) {
            $this->validateAllAfterTranslate($translations);
        }
    }

    /**
     * @param $translations
     * @param $texts
     * @throws Exception
     */
    private function fetchSavedTranslations(&$translations, &$texts)
    {

        $languages = $this->context->getLanguages();

        /*
        * Find translations from DB with ActiveData
        * */
        $models = \NovemBit\i18n\models\Translation::get(
            $this->type,
            $texts,
            $this->context->getFromLanguage(),
            $languages
        );

        foreach ($models as $model) {

            /*
             * Adding saved translations on array
             * */
            $translations[$model['source']][$model['to_language']] = $model['translate'];

            /*
             * Unset texts that already saved in cache
             * */
            if (count($translations[$model['source']]) == count($languages)) {
                unset(
                    $texts[array_search(
                        $model['source'],
                        $texts
                    )]
                );
            }
        }
    }

    /**
     * Method that must be used public for each time
     * To make translations,
     * Its using builtin caching system to
     * Save already translated texts on DB with Active data
     *
     * @param array $texts
     *
     * @return array
     * @throws Exception
     */
    public function translate(array $texts)
    {

        $texts = array_filter($texts);

        /*
         * Remove duplicate texts
         * */
        $texts = array_unique($texts);

        /*
         * Event before translation
         * */
        $this->beforeTranslate($texts);

        /*
         * Result
         * */
        $translations = [];

        /*
         * If use_saved_translations is true
         * Then take try to take translations from DB
         * And unset existing translations from $texts array
         * */
        if ($this->save_translations) {

            $this->fetchSavedTranslations($translations, $texts);

        }

        /*
         * If $texts array not empty then
         * Make new translates
         * */
        if (!empty($texts)) {

            $new_translations = $this->doTranslate($texts);


            /*
             * If save_translations is true
             * Then save new translations on DB
             * */
            if ($this->save_translations) {
                \NovemBit\i18n\models\Translation::saveTranslations(
                    $this->context->getFromLanguage(),
                    $this->type,
                    $new_translations
                );
            }

            /*
             * Merge new and saved translations
             * */
            $translations = $translations + $new_translations;
        }

        /*
         * Event after translate
         * */
        $this->afterTranslate($translations);

        return $translations;
    }

    /**
     * @param array $texts
     *
     * @return array
     * @throws Exception
     */
    public function reTranslate(array $texts)
    {

        if (count($this->context->getLanguages()) != 1) {
            throw new Exception("Language not set or set multiple languages.");
        }

        $language = $this->context->getLanguages()[0];

        $result = [];

        $from_language = $this->context->getFromLanguage();

        $this->beforeReTranslate($texts);

        foreach ($texts as $text) {


            $model = \NovemBit\i18n\models\Translation::get(
                $this->type,
                [$text],
                $from_language,
                [$language],
                true
            );

            if (!isset($model[0]['source'])) {
                continue;
            }

            $result[$text] = $model[0]['source'];

        }

        $this->afterReTranslate($result);

        return $result;

    }


    /**
     * @param $texts
     */
    public function beforeReTranslate(&$texts)
    {
        if ($this->validation == true) {
            $this->validateAllBeforeReTranslate($texts);
        }
    }

    /**
     * @param $texts
     * @param $result
     */
    public function afterReTranslate(&$result)
    {
        if ($this->validation == true) {
            $this->validateAllAfterReTranslate($result);
        }
    }

    /**
     * @param $texts
     */
    public function validateAllBeforeReTranslate(&$texts)
    {

        foreach ($texts as $key => & $text) {
            $original = $text;
            if (!$this->validateBeforeReTranslate($text)) {
                unset($texts[$key]);
            } else {
//                $this->doExclusion($text);
                $this->_re_translate_original_texts[$original] = $text;
            }
        }
        //$texts = array_filter($texts);
        $texts = array_unique($texts);
    }

    /**
     * @param $texts
     * @param $result
     */
    public function validateAllAfterReTranslate(&$result)
    {

        /*
        * Restore translation keys
        * Building result from origin values
        * */
        foreach ($this->_re_translate_original_texts as $before => $after) {

            if ($before != $after && isset($result[$after])) {


                $result[$before] = $result[$after];

                if (!$this->validateAfterReTranslate($before, $after, $result)) {
                    unset($result[$before]);
                }

            }
        }

        /*
         * Unset unnecessary keys from result
         * */
        foreach ($this->_re_translate_original_texts as $before => $after) {
            if ($before != $after) {
                unset($result[$after]);
            }
        }

        unset($this->_re_translate_original_texts);

    }

    /**
     * @param $text
     *
     * @return bool
     */
    public function validateBeforeReTranslate(&$text)
    {
        return true;
    }

    /**
     * @param $before
     * @param $after
     *
     * @param $result
     *
     * @return bool
     */
    public function validateAfterReTranslate($before, $after, &$result)
    {
        return true;
    }


    /**
     * @param $text
     *
     * @return bool
     */
    public function validateBeforeTranslate(&$text)
    {
        return true;
    }

    /**
     * @param $before
     * @param $after
     *
     * @param $translates
     *
     * @return bool
     */
    public function validateAfterTranslate($before, $after, &$translates)
    {
        return true;
    }

    /**
     * @param array $texts
     */
    public function validateAllBeforeTranslate(array &$texts)
    {

        foreach ($texts as $key => & $text) {
            $original = $text;
            if (!$this->validateBeforeTranslate($text)) {
                unset($texts[$key]);
            } else {
                $this->doExclusion($text);
                $this->_translate_original_texts[$original] = $text;
            }
        }
//        $texts = array_filter($texts);
        $texts = array_unique($texts);

    }

    /**
     * @param array $translates
     */
    public function validateAllAfterTranslate(array &$translates)
    {
        /*
         * Restore translation keys
         * Building result from origin values
         * */
        foreach ($this->_translate_original_texts as $before => $after) {


            if (isset($translates[$after])) {

                if ($before != $after) {
                    $translates[$before] = $translates[$after];
                }

                if (!$this->validateAfterTranslate($before, $after, $translates)) {
                    unset($translates[$before]);
                }

            }
        }

        /*
         * Unset unnecessary keys from result
         * */
        foreach ($this->_translate_original_texts as $before => $after) {
            if ($before != $after) {
                unset($translates[$after]);
            }
        }

        $this->_translate_original_texts = [];
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
     * @param        $text
     *
     */
    private function doExclusion(&$text)
    {
        $text = preg_replace(
            array_map(function ($exclusion) {
                return '/(?<=\s|^)(' . preg_quote($exclusion) . ')(?=\s|$)/i';
            }, $this->exclusions),
            $this->exclusion_pattern,
            $text
        );
    }

    /**
     * Abstract method that must be extended from
     * Child methods to translate texts
     *
     * @param array $texts
     *
     * @return array
     */
    protected function doTranslate(array $texts)
    {
        return [];
    }

}