<?php
/**
 * Translation component main abstract
 * php version 7.2.10
 *
 * @category Component\Translation
 * @package  Component\Translation
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation;


use NovemBit\i18n\component\languages\exceptions\LanguageException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\models\exceptions\ActiveRecordException;
use NovemBit\i18n\system\Component;

/**
 * Translation abstract method
 *
 * @category Component\Translation
 * @package  Component\Translation
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
abstract class Translator extends Component implements interfaces\Translator
{

    /**
     * Type id. Using as column value to save on DB
     *
     * @var int
     * */
    public $type = 0;

    /**
     * If true then all translations saving on DB
     *
     * @var bool
     * */
    public $save_translations = true;

    /**
     * If true then methods before and after validation runes
     *
     * @var bool
     * */
    public $validation = false;

    /**
     * Exclusions Array of string exclusions
     *
     * @var array
     * */
    public $exclusions = [];

    /**
     * Model class name of ActiveRecord
     *
     * @var models\Translation
     * */
    public $model_class = models\Translation::class;

    /**
     * Exclusion regex replacement pattern
     *
     * @var string
     * */
    public $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * Keep originals to use for validation
     *
     * @var array
     * */
    private $_translate_original_texts = [];

    /**
     * Keep originals to use for validation
     *
     * @var array
     * */
    private $_re_translate_original_texts = [];

    /**
     * {@inheritdoc}
     *
     * @return void
     * @throws TranslationException
     */
    public function init(): void
    {
        if ($this->save_translations == true && !isset($this->model_class)) {
            throw new TranslationException(
                'Unknown configuration: Property "model_class" is required.'
            );
        }

        $this->_initExclusions();
    }

    /**
     * Before translate method
     *
     * @param array $texts Texts array
     *
     * @return void
     */
    public function beforeTranslate(array &$texts): void
    {

        if ($this->validation == true) {
            $this->validateAllBeforeTranslate($texts);
        }

    }

    /**
     * After translate method
     *
     * @param array $translations Translations array
     *
     * @return void
     */
    public function afterTranslate(array &$translations): void
    {
        if ($this->validation == true) {
            $this->validateAllAfterTranslate($translations);
        }
    }

    /**
     * Fetching saved translates from DB
     * Using \NovemBit\i18n\models\Translation model
     *
     * @param array $translations Referenced variable of translations
     * @param array $texts        Referenced variable of initial texts
     *
     * @return void
     * @throws LanguageException
     * @throws TranslationException
     */
    private function _fetchSavedTranslations(&$translations, &$texts): void
    {

        $languages = $this->context->getLanguages();

        /*
        * Find translations from DB with ActiveData
        * */
        $models = $this->getModels($texts, $languages);

        foreach ($models as $model) {

            /**
             * Adding saved translations on array
             * */
            $translations[$model['source']][$model['to_language']]
                = $model['translate'];

            /**
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
     * @param array $texts Texts array to translate
     *
     * @return array
     * @throws LanguageException
     * @throws TranslationException
     * @throws ActiveRecordException
     */
    public function translate(array $texts): array
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

            $this->_fetchSavedTranslations($translations, $texts);

        }

        /*
         * If $texts array not empty then
         * Make new translates
         * */
        if (!empty($texts)) {

            $new_translations = $this->doTranslate($texts);

            /**
             * If save_translations is true
             * Then save new translations on DB
             * With level *0*
             * And without overwriting old values
             * */
            if ($this->save_translations) {
                $this->saveModels($new_translations, 0, false);
            }

            /**
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
     * Re Translate already translated texts, find sources of
     * Bunch text strings
     *
     * @param array $texts Array of texts
     *
     * @return array
     * @throws exceptions\TranslationException
     * @throws LanguageException
     */
    public function reTranslate(array $texts): array
    {

        if (count($this->context->getLanguages()) != 1) {
            throw new TranslationException(
                "Language not set or set multiple languages."
            );
        }

        $language = $this->context->getLanguages()[0];

        $result = [];

        $this->beforeReTranslate($texts);

        foreach ($texts as $text) {


            $model = $this->getModels([$text], [$language], true);

            if (!isset($model[0]['source'])) {
                continue;
            }

            $result[$text] = $model[0]['source'];

        }

        $this->afterReTranslate($result);

        return $result;

    }


    /**
     * Before Translate method
     *
     * @param array $texts Array of texts
     *
     * @return void
     */
    public function beforeReTranslate(&$texts): void
    {
        if ($this->validation == true) {
            $this->validateAllBeforeReTranslate($texts);
        }
    }

    /**
     * After ReTranslate method
     *
     * @param array $result Referenced array of results
     *
     * @return void
     */
    public function afterReTranslate(&$result): void
    {
        if ($this->validation == true) {
            $this->validateAllAfterReTranslate($result);
        }
    }

    /**
     * Validate all before ReTranslate method
     *
     * @param array $texts Array of texts
     *
     * @return void
     */
    public function validateAllBeforeReTranslate(&$texts): void
    {

        foreach ($texts as $key => & $text) {
            $original = $text;
            if (!$this->validateBeforeReTranslate($text)) {
                unset($texts[$key]);
            } else {
                $this->_re_translate_original_texts[$original] = $text;
            }
        }
        $texts = array_unique($texts);
    }

    /**
     * Validate all after ReTranslate
     *
     * @param array $result Referenced variable array of results
     *
     * @return void
     */
    public function validateAllAfterReTranslate(&$result): void
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
     * Validate before ReTranslate
     *
     * @param string $text Text to validate
     *
     * @return bool
     */
    public function validateBeforeReTranslate(&$text): bool
    {
        return true;
    }

    /**
     * Validate after ReTranslate
     *
     * @param string $before Initial value of string
     * @param string $after  final value of string
     * @param array  $result Referenced variable array of results
     *
     * @return bool
     */
    public function validateAfterReTranslate($before, $after, &$result): bool
    {
        return true;
    }


    /**
     * Validate before translate
     *
     * @param string $text Referenced text variable
     *
     * @return bool
     */
    public function validateBeforeTranslate(&$text): bool
    {
        return true;
    }

    /**
     * Validate after translate
     *
     * @param string $before     initial value of string
     * @param string $after      final value of string
     * @param array  $translates Referenced variable of already translated values
     *
     * @return bool
     */
    public function validateAfterTranslate($before, $after, &$translates): bool
    {
        return true;
    }

    /**
     * Validate all before translate
     *
     * @param array $texts Array of texts to translate
     *
     * @return void
     */
    public function validateAllBeforeTranslate(array &$texts): void
    {
        foreach ($texts as $key => & $text) {
            $original = $text;
            if (!$this->validateBeforeTranslate($text)) {
                unset($texts[$key]);
            } else {
                $this->_doExclusion($text);
                $this->_translate_original_texts[$original] = $text;
            }
        }
        $texts = array_unique($texts);
    }

    /**
     * Validate all after translate
     *
     * @param array $translates Array of translations
     *
     * @return void
     */
    public function validateAllAfterTranslate(array &$translates): void
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

        /**
         * Unset unnecessary keys from result
         *
         * @todo Discus and understand it this part is essential
         * */
        /*foreach ($this->_translate_original_texts as $before => $after) {
            if ($before != $after) {
                unset($translates[$after]);
            }
        }*/

        $this->_translate_original_texts = [];
    }

    /**
     * Initialization of exclusions array
     * Sort exclusions by priority
     *
     * @return void
     */
    private function _initExclusions(): void
    {
        usort(
            $this->exclusions, function ($a, $b) {
                if (strpos($a, $b) !== false) {
                    return false;
                } else {
                    return true;
                }
            }
        );
    }

    /**
     * Doing exclusion from text
     *
     * @param string $text Text to make exclusion from there
     *
     * @return void
     */
    private function _doExclusion(&$text): void
    {
        $text = preg_replace(
            array_map(
                function ($exclusion) {
                    return '/(?<=\s|^)(' . preg_quote($exclusion) . ')(?=\s|$)/i';
                }, $this->exclusions
            ),
            $this->exclusion_pattern,
            $text
        );
    }

    /**
     * Abstract method that must be extended from
     * Child methods to translate texts
     *
     * @param array $texts Texts array to translate
     *
     * @return array
     */
    public function doTranslate(array $texts): array
    {
        return [];
    }

    /**
     * Main method to save translations in DB
     *
     * @param array $translations Translations of texts
     * @param int   $level        Level of translation
     * @param bool  $overwrite    If translation exists, then overwrite value
     * @param array $result       Result about saving
     *
     * @return void
     * @throws ActiveRecordException
     * @throws LanguageException
     */
    public function saveModels(
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ): void {
        $this->model_class::saveTranslations(
            $this->context->getFromLanguage(),
            $translations,
            $level,
            $overwrite,
            $result
        );

        /**
         * To debug saving of models
         *
         * @todo Create method to write logs
         *
         * ```php
         * if (isset($result['errors']) && count($result['errors']) > 0) {
         *      // Something to log
         * }
         * ```
         * */
    }

    /**
     * Main method to get translations from DB
     *
     * @param array $texts        Texts array to translate
     * @param array $to_languages To languages list
     * @param bool  $reverse      Use translate column as source (ReTranslate)
     *
     * @return array
     * @throws LanguageException
     */
    public function getModels(
        $texts,
        $to_languages,
        $reverse = false
    ): array {
        return $this->model_class::get(
            $texts,
            $this->context->getFromLanguage(),
            $to_languages,
            $reverse
        );
    }

}