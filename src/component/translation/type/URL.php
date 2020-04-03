<?php

/**
 * Url translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type;

use Doctrine\DBAL\ConnectionException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\i18n\system\helpers\Strings;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Url translation component
 * Translate urls paths and build fully working url
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 * */
class URL extends Type implements interfaces\URL
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'url';

    /**
     * @var bool
     */
    public $cache_result = true;

    /**
     * @var string
     */
    public $model = models\URL::class;

    /**
     * Path separator
     * Using as whitespace replacement
     *
     * @var string
     * */
    public $path_separator = '-';

    /**
     * If true, then making all path elements lowercase
     *
     * @var bool
     * */
    public $path_lowercase = true;

    /**
     * {@inheritdoc}
     * */
    public $validation = true;

    /**
     * Validate url with parts
     * For each part you can write custom rules
     * If rule is not valid then excluding URL
     *
     * @var array
     * */
    public $url_validation_rules = [];

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\URL::class;

    /**
     * Disable Path translation
     *
     * @var bool
     * */
    public $path_translation = true;

    /**
     * Base domain name
     * Default value is $_SERVER['HTTP_HOST']
     *
     * @var string|null
     * */
    public $base_domain;

    /**
     * You can write custom patterns
     * To exclude path custom parts of path
     * After translate your excluded path will be restored
     *
     * @example `\/var\/.*`
     * */
    public $path_exclusion_patterns = [];

    /**
     * {@inheritDoc}
     *
     * @return void
     * @throws TranslationException
     */
    public function mainInit(): void
    {
        if ($this->path_translation === false) {
            $this->save_translations = false;
        }

        if ($this->base_domain === null && Environment::server('HTTP_HOST')) {
            $this->base_domain = Environment::server('HTTP_HOST');
        }

        parent::mainInit();
    }

    /**
     * Doing translation method
     *
     * @param array  $urls          list of urls
     * @param string $from_language From language
     * @param array  $to_languages  To Languages
     * @param bool   $ignore_cache  Ignore cache
     *
     * @return array
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    protected function doTranslate(
        array $urls,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        $paths = [];
        $paths_to_translate = [];

        foreach ($urls as $url) {
            $parts = $this->getUrlParts($url);
            $path = $parts['path'] ?? '';
            $to_translate = $this->getPathParts($path);
            $paths[] = $to_translate;
            array_push($paths_to_translate, ...$to_translate);
        }

        $translations = $this->path_translation
            ? $this->getPathPartTranslations(
                $paths_to_translate,
                $ignore_cache
            ) : [];

        return $this->buildTranslateResult($paths, $to_languages, $translations);
    }

    /**
     * Validate after translate
     * Concat prefix, body and suffix to avoid that
     * Final url is fully like origin url
     *
     * @param string     $before     initial type of url
     * @param string     $after      final type of url
     * @param array      $translates list of translated urls
     * @param array|null $verbose    Verbose
     *
     * @return bool
     */
    protected function validateAfterTranslate(
        $before,
        $after,
        &$translates,
        ?array &$verbose
    ): bool {
        Strings::getStringsDifference($before, $after, $prefix, $suffix);

        $translates[$before] = $translates[$after];

        foreach ($translates[$before] as $language => &$translate) {
            $translate = $prefix . $translate . $suffix;
            $translate = $this->context->context->localization
                ->addLanguageToUrl(
                    $translate,
                    $language,
                    $this->base_domain
                );
        }

        return parent::validateAfterTranslate(
            $before,
            $after,
            $translates,
            $verbose
        );
    }

    /**
     * Prepare Url to process
     * Remove all exclusions from URL
     * Use validation rules to validate parts of uri
     *
     * @param string $url URL
     *
     * @return bool
     */
    protected function prepareUrlToProcess(string &$url): bool
    {
        $url = trim($url, ' ');

        $url = $this->removeExclusionsFromUrl($url);

        $parts = parse_url($url);

        foreach ($this->url_validation_rules as $key => $rules) {
            if (!isset($parts[$key])) {
                $parts[$key] = '';
            }
            foreach ($rules as $rule) {
                if (!preg_match("/$rule/", $parts[$key])) {
                    return false;
                }
            }
        }

        $url = $parts['path'] ?? '';

        $url = $this->context->context->localization->removeScriptNameFromUrl($url);

        $url = rtrim($url, '/');

        return true;
    }

    /**
     * Validate before translate
     * Take parts that must be preserved to concat
     * after translate paths
     *
     *  Removing script name from url to make avoid
     *  that translatable part of url is only working path
     *
     * @param string $url Translatable url
     *
     * @return bool
     */
    protected function validateBeforeTranslate(string &$url): bool
    {
        return $this->prepareUrlToProcess($url);
    }


    /**
     * Building translation result that
     * must be returned
     *
     * @param array $paths        whole translatable paths
     * @param array $languages    list of languages
     * @param array $translations translated paths
     *
     * @return array
     */
    private function buildTranslateResult(
        array $paths,
        array $languages,
        array $translations
    ): array {
        $result = [];

        foreach ($paths as $path_parts) {
            $path = implode('/', $path_parts);
            /*
             * To build last result fetching languages
             * And building result with language keys
             * */
            foreach ($languages as $language) {
                $language_path_parts = $path_parts;

                foreach ($language_path_parts as &$part) {
                    /*
                     * If translation found
                     * */
                    if (isset($translations[$part])) {
                        $part = $translations[$part][$language] ?? $part;
                        /**
                         * If translator returns string that contains
                         * Unwanted characters, then clarifying string
                         * */
                        $part = $this->preparePathPart($part);
                    }
                }
                unset($part);

                /**
                 * Restore path trailing slashes
                 * */
                $translate = implode('/', $language_path_parts);

                /*
                 * Appending translated url to result
                 * */
                $result[$path][$language] = $translate;
            }
        }

        return $result;
    }

    /**
     * Get path translations
     * Translate with translation component as text
     *
     * @param array $paths        paths to translate
     * @param bool  $ignore_cache Ignore cache
     *
     * @return mixed
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    private function getPathPartTranslations(array $paths, $ignore_cache)
    {
        foreach ($paths as &$string) {
            $string = (string)$string;
        }
        unset($string);

        $translator = $this->context->text;

        return $translator->translate(
            $paths,
            $verbose,
            false,
            $ignore_cache
        );
    }

    /**
     * Get path parts slash delimited
     * Explode path to get parts of path as array
     *
     * @param string $path path to split parts
     *
     * @return array
     */
    private function getPathParts($path): array
    {
        $path = trim($path, '/');

        /*
         * Separate path parts
         * */
        return explode('/', $path);
    }

    /**
     * Get url parts, using @parse_url PHP method
     *
     * @param string $url parsable URL
     *
     * @return mixed
     */
    private function getUrlParts($url)
    {
        return parse_url($url);
    }

    /**
     * Clarify and prepare path part
     * Using before translate
     *
     * @param string $url Simple url
     *
     * @return string|null
     */
    private function preparePathPart($url): ?string
    {
        /**
         * Remove all html special characters
         *
         * @source https://stackoverflow.com/a/657670
         * */
        $url = preg_replace('/&#?[a-z0-9]{2,8};/i', '', $url);

        /**
         * Replace all non-alphanumeric characters to whitespace
         * To make url SEO friendly
         * */
        $url = preg_replace('/(?:(?!\.)\W|_)+/u', $this->path_separator, $url);

        /**
         * Remove "-" symbol from start and end of string
         * */
        $url = trim($url, $this->path_separator);

        if ($this->path_lowercase) {
            /**
             * String to lowercase
             * */
            $url = mb_strtolower($url);
        }

        return $url;
    }

    /**
     * Remove exclusions from url
     *
     * @param string $url URL
     *
     * @return string
     */
    protected function removeExclusionsFromUrl(string $url): string
    {
        $path_exclusion_patterns = array_filter($this->path_exclusion_patterns);

        foreach ($path_exclusion_patterns as $pattern) {
            $url = preg_replace($pattern, '', $url);
        }

        return $url ?? '';
    }

    /**
     * Validate URL before ReTranslate
     *
     * @param string $url Re translatable URL
     *
     * @return bool
     */
    protected function validateBeforeReTranslate(&$url): bool
    {
        return $this->prepareUrlToProcess($url);
    }

    /**
     * Validate result after ReTranslate
     *  Remove language key from query variables
     *
     * @param string $before initial url
     * @param string $after  final url
     * @param array  $result Referenced variable to receive result
     *
     * @return bool
     */
    protected function validateAfterReTranslate($before, $after, &$result): bool
    {
        Strings::getStringsDifference($before, $after, $prefix, $suffix);

        if ($before !== $after && isset($result[$after])) {
            $result[$before] = $prefix . $result[$after] . $suffix;
        }

        $result[$before] = \NovemBit\i18n\system\helpers\URL::removeQueryVars(
            $result[$before],
            $this->context->context->localization->getLanguageQueryKey()
        );

        return parent::validateAfterReTranslate(
            $before,
            $after,
            $result
        );
    }

    /**
     *
     * @return bool
     */
    public function isPathTranslation(): bool
    {
        return $this->path_translation;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $from_language From language
     * @param array  $to_languages  To languages list
     * @param array  $texts         Texts to translate
     *
     * @return string
     */
    protected function getCacheKey(
        string $from_language,
        array $to_languages,
        array $texts
    ): string {
        $prefix = '';
        $http_host = md5(Environment::server('HTTP_HOST'));
        if ($http_host !== null) {
            $http_host = str_replace('.', '_', $http_host);
            $prefix = $http_host . '_';
        }

        return $prefix . parent::getCacheKey($from_language, $to_languages, $texts);
    }
}
