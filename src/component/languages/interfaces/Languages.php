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
     * Adding language code to
     * Already translated URL
     *
     * If `$language_on_path` is true then adding
     * Language code to beginning of url path
     *
     * If `$language_on_path` is false or url contains
     * Script name or directory path then adding only
     * Query parameter of language
     *
     * @param string      $url         Initial Url
     * @param string      $language    Language code
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function addLanguageToUrl(
        string $url,
        string $language,
        ?string $base_domain = null
    );

    /**
     * Validate one language
     * Check if language exists in `$accepted_languages` array
     *
     * @param string $language Language code
     *
     * @return mixed
     */
    public function validateLanguage(string $language): bool;

    /**
     * Validate list of Languages
     * Check if each language code exists on
     * Accepted languages list
     *
     * @param string[] $languages language codes
     *
     * @return bool
     */
    public function validateLanguages(array $languages): bool;

    /**
     * Get main content (from) language
     *
     * @return string
     */
    public function getFromLanguage(): string;

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
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function getDefaultLanguage(?string $base_domain = null): string;

    /**
     * Get default country
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     * */
    public function getDefaultCountry(?string $base_domain = null): ?string;

    /**
     * Get default country
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     * */
    public function getDefaultRegion(?string $base_domain = null): ?string;

    /**
     * Remove executable file from url path
     *
     * @param string $url Initial URL
     *
     * @return string
     */
    public function removeScriptNameFromUrl(string $url): string;

    /**
     * Get language query key
     *
     * @return string
     */
    public function getLanguageQueryKey(): string;

    /**
     * Get accept languages
     *
     * @param bool $with_names With names
     * @param bool $with_flags With flags
     *
     * @return array
     */
    public function getAcceptLanguages(
        bool $with_names = false,
        bool $with_flags = false
    ): array;

    /**
     * Get default config by `$base_domain` name
     *
     * @param string|null $base_domain base domain name
     *
     * @return array
     */
    public function getDefaultConfig(?string $base_domain = null): array;

    /**
     * Get flag of language country
     *
     * @param string $language Language code
     * @param bool   $html     return html <img src="..
     *
     * @return string
     */
    public function getFlagByLanguage(string $language, $html = false): string;
}
