<?php
/**
 * Translation component main abstract
 * php version 7.2.10
 *
 * @category Component
 * @package  Composer
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation;


use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\system\Component;

/**
 * Translation abstract method
 *
 * @category Class
 * @package  HTML
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property int type Type id. Using as column value to save on DB
 * @property bool save_translations If true then all translations saving on DB
 * @property bool validation If true then methods before and after validation runes
 * @property array exclusions Array of string exclusions
 * @property string exclusion_pattern Regexp replacement pattern of exclusion
 * @property array _translate_original_texts keep originals to use for validation
 * @property array _re_translate_original_texts keep originals to use for validation
 * @property \NovemBit\i18n\component\Translation context
 */
abstract class Translation extends Component
{
    public $type = 0;

    public $save_translations = true;

    public $validation = false;

    public $exclusions = [];

    public $exclusion_pattern = '<span translate="no">$0</span>';

    private $_translate_original_texts = [];

    private $_re_translate_original_texts = [];

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function init()
    {
        $this->_initExclusions();
    }

    /**
     * Before translate method
     *
     * @param array $texts Texts array
     *
     * @return void
     */
    public function beforeTranslate(array &$texts)
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
    public function afterTranslate(array &$translations)
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
     * @throws Exception
     */
    private function _fetchSavedTranslations(&$translations, &$texts)
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
            $translations[$model['source']][$model['to_language']]
                = $model['translate'];

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
     * @param array $texts Texts array to translate
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

            $this->_fetchSavedTranslations($translations, $texts);

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
     * Re Translate already translated texts, find sources of
     * Bunch text strings
     *
     * @param array $texts Array of texts
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
     * Before Translate method
     *
     * @param array $texts Array of texts
     *
     * @return void
     */
    public function beforeReTranslate(&$texts)
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
    public function afterReTranslate(&$result)
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
    public function validateAllBeforeReTranslate(&$texts)
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
     * Validate before ReTranslate
     *
     * @param string $text Text to validate
     *
     * @return bool
     */
    public function validateBeforeReTranslate(&$text)
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
    public function validateAfterReTranslate($before, $after, &$result)
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
    public function validateBeforeTranslate(&$text)
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
    public function validateAfterTranslate($before, $after, &$translates)
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
    public function validateAllBeforeTranslate(array &$texts)
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
     * Initialization of exclusions array
     * Sort exclusions by priority
     *
     * @return void
     */
    private function _initExclusions()
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
    private function _doExclusion(&$text)
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
    protected function doTranslate(array $texts)
    {
        return [];
    }

}