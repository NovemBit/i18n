<?php

namespace NovemBit\i18n\component\localization\languages\interfaces;

use NovemBit\i18n\component\localization\interfaces\LocalizationType;

/**
 * @property
 * */
interface Languages extends LocalizationType
{

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    public function getLanguagesMap(string $key, string $value): array;

    /**
     * Get flag of language country
     *
     * @param  string  $code  Language code
     * @param  bool  $html  return html <img src="..
     *
     * @return string
     */
    public function getLanguageFlagByCode(
        string $code,
        bool $rectangle = true,
        bool $html = false
    ): string;

    /**
     * Get language whole data
     *
     * @param  string  $language_key  Language key
     *
     * @return array
     */
    public function getLanguageData(string $language_key): array;

    /**
     * Get language name by code
     *
     * @param string $code Language key
     *
     * @return string
     */
    public function getLanguageNameByCode(string $code): string;

    public function getLanguageDirectionByCode(string $code): string;
}
