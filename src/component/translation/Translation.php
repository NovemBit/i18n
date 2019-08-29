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
         * Getting languages from context
         * */
        $languages = $this->context->getLanguages();

        /*
         * Result
         * */
        $translations = [];

        /*
         * If use_saved_translations is true
         * Then take try to take translations from DB
         * And unset existing translations from $texts array
         * */
        if ($this->use_saved_translations) {

            /*
             * Find translations from DB with ActiveData
             * */
            $saved_translations_models = models\TranslationNode::findTranslations(
                $this->type,
                $texts,
                $this->context->context->languages->from_language,
                $languages
            );


            foreach ($saved_translations_models as $saved_translation) {

                /*
                 * Adding saved translations on array
                 * */
                $translations[$saved_translation['source']][$saved_translation['to_language']]
                    = $saved_translation['translate'];

                /*
                 * Unset texts that already saved in cache
                 * */

                if (count($translations[$saved_translation['source']])
                    == count($languages)
                ) {
                    unset(
                        $texts[array_search(
                            $saved_translation['source'],
                            $texts
                        )]
                    );
                }
            }
        }


        /*
         * If $texts array not empty then
         * Make new translates
         * */
        if ( ! empty($texts)) {

            $new_translations = $this->doTranslate($texts);

            /*
             * If save_translations is true
             * Then save new translations on DB
             * */
            if ($this->save_translations) {
                models\TranslationNode::saveTranslations(
                    $this->context->context->languages->from_language,
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

        $default_language = $this->context->context->languages->from_language;

        $this->beforeReTranslate($texts);

        foreach ($texts as $text) {


            $model = models\TranslationNode::findTranslations(
                $this->type,
                [$text],
                $default_language,
                [$language],
                false
            );

            if ( ! isset($model[0]['source'])) {
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
            if ( ! $this->validateBeforeReTranslate($text)) {
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

                if ( ! $this->validateAfterReTranslate($before, $after, $result)) {
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
            if ( ! $this->validateBeforeTranslate($text)) {
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

            if ($before != $after && isset($translates[$after])) {

                $translates[$before] = $translates[$after];

                if ( ! $this->validateAfterTranslate($before, $after, $translates)) {
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

        unset($this->_translate_original_texts);
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