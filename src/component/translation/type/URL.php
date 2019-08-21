<?php

namespace NovemBit\i18n\component\translation\type;

use NovemBit\i18n\component\Translation;

/**
 * @property  Translation context
 */
class URL extends Type
{

    public $type = 2;

    public $path_separator = "-";

    public $path_lowercase = true;

    /**
     * @param array $urls
     *
     * @return array
     * @throws \Exception
     */
    public function doTranslate(array $urls)
    {

        $languages          = $this->context->getLanguages();
        $paths_to_translate = [];

        foreach ($urls as $url) {
            $parts              = $this->getUrlParts($url);
            $path               = isset($parts['path']) ? $parts['path'] : "";
            $to_translate       = $this->getPathPartsToTranslate($path);
            $paths_to_translate = $paths_to_translate + $to_translate;
        }

        $translations
            = $this->getPathPartTranslations(array_keys($paths_to_translate));

        $result = $this->buildTranslateResult($urls, $languages, $translations);

        return $result;
    }

    /**
     * @param array $urls
     * @param array $languages
     * @param array $translations
     *
     * @return array
     */
    private function buildTranslateResult(
        array $urls,
        array $languages,
        array $translations
    ) {
        $result = [];
        foreach ($urls as $url) {

            /**
             * Parse url to parts
             * */
            $url_parts = $this->getUrlParts($url);

            /**
             * Getting path parts slash delimited
             * */
            $path_parts = isset($url_parts['path'])
                ? $this->getPathParts($url_parts['path']) : [];

            /*
             * To build last result fetching languages
             * And building result with language keys
             * */
            foreach ($languages as $language) {

                $language_path_parts = $path_parts;

                foreach ($language_path_parts as &$part) {

                    $part = $translations[$part][$language];

                    /**
                     * If translator returns string that contains
                     * Unwanted characters, then clarifying string
                     * */
                    $part = $this->preparePathPart($part);
                }

                /**
                 * Restore path trailing slashes
                 * */
                $url_parts['path'] = '/' . implode('/', $language_path_parts);

                /*
                 * Appending translated url to result
                 * */
                $result[$url][$language] = $this->build_url($url_parts);
            }
        }

        return $result;
    }

    /**
     * Build url from already parsed url
     *
     * Reverse of function parse_url
     *
     * @param array $parts
     *
     * @return string
     */
    private function build_url(array $parts)
    {
        $scheme   = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';
        $host     = $parts['host'] ?? '';
        $port     = isset($parts['port']) ? (':' . $parts['port']) : '';
        $user     = $parts['user'] ?? '';
        $pass     = isset($parts['pass']) ? (':' . $parts['pass']) : '';
        $pass     = ($user || $pass) ? ($pass . '@') : '';
        $path     = $parts['path'] ?? '';
        $query    = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return implode('',
            [$scheme, $user, $pass, $host, $port, $path, $query, $fragment]);
    }

    /**
     * @param $path
     *
     * @return array
     */
    private function getPathPartsToTranslate(string $path)
    {

        $result = [];

        $parts = $this->getPathParts($path);
        foreach ($parts as $part) {
            $result[$part] = null;
        }

        return $result;
    }

    /**
     * @param array $strings
     *
     * @return mixed
     * @throws \Exception
     */
    private function getPathPartTranslations(array $strings)
    {

        $translate = $this->context->text->translate($strings);

        return $translate;
    }

    /**
     * Get path parts slash delimited
     *
     * @param $path
     *
     * @return array
     */
    private function getPathParts($path)
    {

        $path = trim($path, '/');

        /*
         * Separate path parts
         * */
        $parts = explode('/', $path);

        foreach ($parts as &$part) {

            /*
             * Clarify parts
             * Remove unwanted characters
             * */
            $part = $this->preparePathPart($part);
        }

        return $parts;
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    private function getUrlParts($url)
    {
        $parts = parse_url($url);

        return $parts;
    }

    /**
     * @param $string
     *
     * @return string|null
     */
    private function preparePathPart($string)
    {
        /**
         * Remove all html special characters
         * @source https://stackoverflow.com/a/657670
         * */
        $string = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $string);

        /**
         * Replace all non-alphanumeric characters to whitespace
         * To make url SEO friendly
         * */
        $string = preg_replace('/(?:\W|_)+/u', $this->path_separator, $string);

        /**
         * Remove "-" symbol from start and end of string
         * */
        $string = trim($string, $this->path_separator);

        if ($this->path_lowercase !== false) {
            /**
             * String to lowercase
             * */
            $string = strtolower($string);
        }

        return $string;
    }
}