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

use Doctrine\DBAL\ConnectionException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Translation abstract method
 *
 * @category Component\Translation
 * @package  Component\Translation
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property \NovemBit\i18n\component\translation\interfaces\Translation context
 */
abstract class Translator extends Component implements interfaces\Translator
{

    /**
     * Name of public method
     * */
    public string $name;

    /**
     * Cache last result of translation
     * */
    public bool $cache_result = false;

    /**
     * Cache TTL
     * */
    public int $cache_result_ttl = 3600;

    /**
     * If true then all translations saving on DB
     * */
    public bool $save_translations = true;

    /**
     * If true then methods before and after validation runes
     * */
    public bool $validation = false;

    /**
     * Exclusions Array of string exclusions
     * */
    public array $exclusions = [];

    /**
     * Model class name of ActiveRecord
     * */
    public string $model_class = models\Translation::class;

    /**
     * Exclusion regex replacement pattern
     * */
    public string $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * Keep originals to use for validation
     * */
    private array $translate_original_texts = [];

    /**
     * Keep originals to use for validation
     * */
    private array $re_translate_original_texts = [];

    /**
     * Show helper attributes that contains
     * All information about current node and child Text/Attr nodes
     * */
    private array $helper_attributes = [];

    /**
     * {@inheritdoc}
     *
     * @return void
     * @throws TranslationException
     */
    public function mainInit(): void
    {
        if ($this->save_translations == true && ! isset($this->model_class)) {
            throw new TranslationException(
                'Unknown configuration: Property "model_class" is required.'
            );
        }

        $this->initExclusions();
    }

    /**
     * Before translate method
     *
     * @param  array  $texts  Texts array
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
     * @param  array  $translations  Translations array
     * @param  array|null  $verbose  Verbose
     *
     * @return void
     */
    public function afterTranslate(
        array &$translations,
        ?array &$verbose
    ): void {
        if ($this->validation == true) {
            $this->validateAllAfterTranslate($translations, $verbose);
        }
    }

    /**
     * Fetching saved translates from DB
     * Using \NovemBit\i18n\models\Translation model
     *
     * @param  string  $from_language  From language
     * @param  array  $to_languages  To languages array
     * @param  array  $translations  Referenced variable of translations
     * @param  array  $texts  Referenced variable of initial texts
     * @param  array|null  $verbose  Information about progress
     *
     * @return void
     */
    private function fetchSavedTranslations(
        string $from_language,
        array $to_languages,
        array &$translations,
        array &$texts,
        ?array &$verbose
    ): void {
        /**
         * Find translations from DB with ActiveData
         * */
        $models = $this->getModels(
            $texts,
            $from_language,
            $to_languages
        );

        foreach ($models as $model) {
            /**
             * Adding saved translations on array
             * */
            if ( ! isset($translations[$model['source']][$model['to_language']])) {
                $translations[$model['source']][$model['to_language']]
                    = $model['translate'];
            }

            /**
             * Take level/created_at/updated_at
             * from model only if with_verbose variable is true
             * */
            if (
                $verbose !== null
                && ! isset($verbose[$model['source']][$model['to_language']]['id'])
            ) {
                $verbose[$model['source']][$model['to_language']]['id']
                    = $model['id'] ?? null;
                $verbose[$model['source']][$model['to_language']]['translate']
                    = $model['translate'] ?? null;
                $verbose[$model['source']][$model['to_language']]['level']
                    = $model['level'] ?? null;
            }

            /**
             * Unset texts that already saved in cache
             * */
            if (count($translations[$model['source']]) == count($to_languages)) {
                $keys_to_unset = array_keys($texts, $model['source']);
                foreach ($keys_to_unset as $key) {
                    unset($texts[$key]);
                }
            }
        }
    }

    /**
     * Get cache key for combination
     *
     * @param  string  $from_language  From language
     * @param  array  $to_languages  To languages array
     * @param  array  $texts  Texts array
     *
     * @return string
     */
    protected function getCacheKey(
        string $from_language,
        array $to_languages,
        array $texts
    ): string {
        return sprintf(
            "%s_%s_%s_%s",
            $this->name,
            $from_language,
            implode('_', $to_languages),
            md5(json_encode($texts))
        );
    }

    /**
     * Method that must be used public for each time
     * To make translations, its using builtin caching system to
     * Save already translated texts on DB with Active data
     *
     * @param  array  $texts  Texts array to translate
     * @param  array|null  $verbose  Information about translation progress
     * @param  bool  $only_saved  Dont make new translate and return only saved
     * @param  bool  $ignore_cache  Ignore cached result
     *
     * @return array
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function translate(
        array $texts,
        ?array &$verbose = null,
        bool $only_saved = false,
        bool $ignore_cache = false
    ): array {
        $from_language = $this->context->getFromLanguage();
        $to_languages  = $this->context->getLanguages();

        if ($this->isCacheResult() === true && ! $ignore_cache) {
            $cache_key = $this->getCacheKey($from_language, $to_languages, $texts);

            $cache = $this->getCachePool()
                          ->get($cache_key, null);

            if ($cache !== null) {
                return $cache;
            }
        }

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
         * If save_translations is true
         * Then take try to take translations from DB
         * And unset existing translations from $texts array
         * */
        if ($this->save_translations) {
            $this->fetchSavedTranslations(
                $from_language,
                $to_languages,
                $translations,
                $texts,
                $verbose
            );
        }

        /*
         * If $texts array not empty then
         * Make new translates
         * */
        if ( ! $only_saved && ! empty($texts)) {
            $new_translations = $this->doTranslate(
                $texts,
                $from_language,
                $to_languages,
                $ignore_cache
            );

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
            $translations = Arrays::arrayMergeRecursiveDistinct(
                $translations,
                $new_translations
            );
        }

        /*
         * Event after translate
         * */
        $this->afterTranslate($translations, $verbose);

        if (isset($cache_key)) {
            $this->getCachePool()->set(
                $cache_key,
                $translations,
                $this->cache_result_ttl
            );
        }

        return $translations;
    }

    /**
     * Re Translate already translated texts, find sources of
     * Bunch text strings
     *
     * @param  array  $texts  Array of texts
     *
     * @return array
     * @throws exceptions\TranslationException
     */
    public function reTranslate(array $texts): array
    {
        $from_language = $this->context->getFromLanguage();
        $to_languages  = $this->context->getLanguages();

        $this->beforeReTranslate($texts);

        $result = $this->doReTranslate($texts, $to_languages[0], [$from_language]);

        $this->afterReTranslate($result);

        return $result;
    }

    protected function doReTranslate(
        array $texts,
        string $to_language,
        array $from_languages
    ): array {
        $result = [];

        foreach ($texts as $text) {
            $model = $this->getReversedModels([$text], $to_language, $from_languages);
            if ( ! isset($model[0]['source'])) {
                continue;
            }

            $result[$model[0]['to_language']][$text] = $model[0]['source'];
        }

        return $result;
    }

    /**
     * Before Translate method
     *
     * @param  array  $texts  Array of texts
     *
     * @return void
     */
    protected function beforeReTranslate(&$texts): void
    {
        if ($this->validation == true) {
            $this->validateAllBeforeReTranslate($texts);
        }
    }

    /**
     * After ReTranslate method
     *
     * @param  array  $result  Referenced array of results
     *
     * @return void
     */
    protected function afterReTranslate(&$result): void
    {
        if ($this->validation == true) {
            $this->validateAllAfterReTranslate($result);
        }
    }

    /**
     * Validate all before ReTranslate method
     *
     * @param  array  $texts  Array of texts
     *
     * @return void
     */
    protected function validateAllBeforeReTranslate(array &$texts): void
    {
        foreach ($texts as $key => & $text) {
            $original = $text;
            if ( ! $this->validateBeforeReTranslate($text)) {
                unset($texts[$key]);
            } else {
                $this->re_translate_original_texts[$original] = $text;
            }
        }
        $texts = array_unique($texts);
    }

    /**
     * Validate all after ReTranslate
     *
     * @param  array  $result  Referenced variable array of results
     *
     * @return void
     */
    protected function validateAllAfterReTranslate(&$result): void
    {
        /*
        * Restore translation keys
        * Building result from origin values
        * */
        foreach ($this->re_translate_original_texts as $before => $after) {
            foreach ($result as &$language_result) {
                if ($before != $after && isset($language_result[$after])) {
                    $language_result[$before] = $language_result[$after];

                    if ( ! $this->validateAfterReTranslate($before, $after, $language_result)) {
                        unset($language_result  [$before]);
                    }
                }
            }
        }

        /*
         * Unset unnecessary keys from result
         * */
        foreach ($this->re_translate_original_texts as $before => $after) {
            foreach ($result as &$language_result) {
                if ($before != $after) {
                    unset($language_result[$after]);
                }
            }
        }

        $this->re_translate_original_texts = [];
    }

    /**
     * Validate before ReTranslate
     *
     * @param  string  $text  Text to validate
     *
     * @return bool
     */
    protected function validateBeforeReTranslate(&$text): bool
    {
        return true;
    }

    /**
     * Validate after ReTranslate
     *
     * @param  string  $before  Initial value of string
     * @param  string  $after  final value of string
     * @param  array  $result  Referenced variable array of results
     *
     * @return bool
     */
    protected function validateAfterReTranslate(string $before, string $after, array &$result): bool
    {
        return true;
    }

    /**
     * Validate before translate
     *
     * @param  string  $text  Referenced text variable
     *
     * @return bool
     */
    protected function validateBeforeTranslate(string &$text): bool
    {
        return true;
    }

    /**
     * Validate after translate
     *
     * @param  string  $before  initial value of string
     * @param  string  $after  final value of string
     * @param  array  $translates  Referenced variable of already translated values
     * @param  array|null  $verbose  Verbose
     *
     * @return bool
     */
    protected function validateAfterTranslate(
        string $before,
        string $after,
        array &$translates,
        ?array &$verbose
    ): bool {
        $verbose[$before]['after'] = $after;

        return true;
    }

    /**
     * Validate all before translate
     *
     * @param  array  $texts  Array of texts to translate
     *
     * @return void
     */
    protected function validateAllBeforeTranslate(array &$texts): void
    {
        foreach ($texts as $key => & $text) {
            $original = $text;
            if ( ! $this->validateBeforeTranslate($text)) {
                unset($texts[$key]);
            } else {
                $this->doExclusion($text);
                $this->translate_original_texts[$original] = $text;
            }
        }
        $texts = array_unique($texts);
    }

    /**
     * Validate all after translate
     *
     * @param  array  $translates  Array of translations
     * @param  array|null  $verbose  Verbose
     *
     * @return void
     */
    protected function validateAllAfterTranslate(
        array &$translates,
        ?array &$verbose
    ): void {
        /*
         * Restore translation keys
         * Building result from origin values
         * */
        foreach ($this->translate_original_texts as $before => $after) {
            if (isset($translates[$after])) {
                if ($before != $after) {
                    $translates[$before] = $translates[$after];
                    if ($verbose !== null) {
                        $verbose[$before] = $verbose[$after] ?? null;
                    }
                }

                if (
                ! $this->validateAfterTranslate(
                    $before,
                    $after,
                    $translates,
                    $verbose
                )
                ) {
                    unset($translates[$before]);
                    unset($verbose[$before]);
                }
            }
        }

        /**
         * Unset unnecessary keys from result
         *
         * @todo Discus and understand if this part is essential
         * */
        /*foreach ($this->_translate_original_texts as $before => $after) {
            if ($before != $after) {
                unset($translates[$after]);
            }
        }*/

        $this->translate_original_texts = [];
    }

    /**
     * Initialization of exclusions array
     * Sort exclusions by priority
     *
     * @return void
     */
    private function initExclusions(): void
    {
        usort(
            $this->exclusions,
            function ($a, $b) {
                if (strpos($a, $b) !== false) {
                    return 0;
                } else {
                    return 1;
                }
            }
        );
    }

    /**
     * Doing exclusion from text
     *
     * @param  string  $text  Text to make exclusion from there
     *
     * @return void
     */
    private function doExclusion(&$text): void
    {
        $text = preg_replace(
            array_map(
                function ($exclusion) {
                    return '/(?<=\s|^)(' . preg_quote($exclusion) . ')(?=\s|$)/i';
                },
                $this->exclusions
            ),
            $this->exclusion_pattern,
            $text
        );
    }

    /**
     * Abstract method that must be extended from
     * Child methods to translate texts
     *
     * @param  array  $texts  Texts array to translate
     * @param  string  $from_language  From language
     * @param  array  $to_languages  To languages array
     * @param  bool  $ignore_cache  Ignore cache
     *
     * @return array
     */
    protected function doTranslate(
        array $texts,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        return [];
    }

    /**
     * Main method to save translations in DB
     *
     * @param  array  $translations  Translations of texts
     * @param  int  $level  Level of translation
     * @param  bool  $overwrite  If translation exists, then overwrite value
     * @param  array  $result  Result about saving
     *
     * @return void
     * @throws ConnectionException
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
     * @param  array  $texts  Texts array to translate
     * @param  string  $from_language  From language
     * @param  array  $to_languages  To languages list
     *
     * @return array
     */
    public function getModels(
        array $texts,
        string $from_language,
        array $to_languages
    ): array {
        return $this->model_class::get(
            $texts,
            $from_language,
            $to_languages
        );
    }

    /**
     * Main method to get translations from DB
     *
     * @param  array  $texts  Texts array to translate
     * @param  string  $to_language
     * @param  array  $from_languages
     *
     * @return array
     */
    public function getReversedModels(
        array $texts,
        string $to_language,
        array $from_languages
    ): array {
        return $this->model_class::getReversed(
            $texts,
            $to_language,
            $from_languages
        );
    }

    /**
     * Get context translation component
     *
     * @return interfaces\Translation
     */
    public function getTranslation()
    {
        return $this->context;
    }

    /**
     * If Cache Result enabled
     *
     * @return bool|null
     */
    public function isCacheResult(): ?bool
    {
        return $this->cache_result;
    }

    /**
     * Set cache result status
     *
     * @param  bool  $cache_status  Status
     *
     * @return void
     */
    public function setCacheResult(?bool $cache_status): void
    {
        $this->cache_result = $cache_status;
    }

    /**
     * @return bool
     * */
    public function getHelperAttributes(): array
    {
        return $this->helper_attributes;
    }

    /**
     * @param  array  $types  Types list to get on helper attributes
     *                     e.g ['text','url']
     *
     * @return void
     * */
    public function setHelperAttributes(array $types): void
    {
        $this->helper_attributes = $types;
    }
}
