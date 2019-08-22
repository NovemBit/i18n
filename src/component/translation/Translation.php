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

    public function init()
    {

    }

    /**
     * Method that must be used publicly for each time
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
        $languages = $this->context->getLanguages();

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
            $saved_translations_models = models\Translation::findTranslations(
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
                    unset($texts[array_search($saved_translation['source'],
                            $texts)]);
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
                models\Translation::saveTranslations(
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


        return $translations;
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