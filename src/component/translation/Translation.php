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

    private $_original_texts = [];

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
            $this->validateAllBefore($texts);
        }

    }

    /**
     * @param array $translations
     */
    public function afterTranslate(array &$translations)
    {
        if ($this->validation == true) {
            $this->validateAllAfter($translations);
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
                $this->context->context->languages->default_language,
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
                    $this->context->context->languages->default_language,
                    $this->type,
                    $new_translations
                );
            }

            /*
             * Merge new and saved translations
             * */
            $translations = array_merge($translations,
                $new_translations);
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

        $default_language
            = $this->context->context->languages->default_language;

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

        return $result;

    }

    /**
     * @param $text
     *
     * @return bool
     */
    public function validateBefore(&$text)
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
    public function validateAfter($before, $after, &$translates)
    {
        return true;
    }

    /**
     * @param array $texts
     */
    public function validateAllBefore(array &$texts)
    {
        foreach ($texts as $key => & $text) {
            $original = $text;
            if ( ! $this->validateBefore($text)) {
                unset($texts[$key]);
            } else {
                $this->doExclusion($text);
                $this->_original_texts[$original] = $text;
            }
        }

    }

    /**
     * @param array $translates
     */
    public function validateAllAfter(array &$translates)
    {
        foreach ($this->_original_texts as $before => $after) {
            if ($before != $after && isset($translates[$after])) {
                $translates[$before] = $translates[$after];

                if ( ! $this->validateAfter($before, $after, $translates)) {
                    unset($translates[$before]);
                }

                unset($translates[$after]);

            }
        }

        unset($this->_original_texts);
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