<?php


namespace NovemBit\i18n\component\languages\interfaces;

/**
 * Languages component interface
 * */
interface Languages
{

    /**
     * Get language code from url
     *
     * @param string|null $url Initial URL
     *
     * @return string
     */
    public function getLanguageFromUrl(string $url);

    /**
     * Add language
     *
     * @param string $url      Initial Url
     * @param string $language Language code
     *
     * @return string
     */
    public function addLanguageToUrl(string $url, string $language);

    /**
     * Validate language code
     *
     * @param string $language Language code
     *
     * @return mixed
     */
    public function validateLanguage(string $language) : bool;

    /**
     * Validate multiple languages
     *
     * @param array $languages Languages list
     *
     * @return bool
     */
    public function validateLanguages(array $languages) : bool;

    /**
     * Get main content (from) language
     *
     * @return string
     */
    public function getFromLanguage() : string;

    /**
     * Set main from language
     *
     * @param string $from_language from language code
     *
     * @return void
     */
    public function setFromLanguage(string $from_language): void;

    /**
     * Get default language
     *
     * @return string
     */
    public function getDefaultLanguage() : string;

    /**
     * Remove string name from url
     *
     * @param string $url Initial URL
     *
     * @return string
     */
    public function removeScriptNameFromUrl(string $url) : string;

    /**
     * Get language query key
     *
     * @return string
     */
    public function getLanguageQueryKey() : string;

    public function getAcceptLanguages(
        bool $with_names = false,
        bool $with_flags = false
    ) : array;

}
