<?php

namespace NovemBit\i18n\component\localization\interfaces;

use NovemBit\i18n\component\localization\countries\interfaces\Countries;
use NovemBit\i18n\component\localization\languages\interfaces\Languages;
use NovemBit\i18n\component\localization\regions\interfaces\Regions;
use NovemBit\i18n\system\interfaces\Component;

/**
 * Languages component interface
 *
 * @property Languages $languages
 * @property Regions $regions
 * @property Countries $countries
 * */
interface Localization extends Component
{
    public function getGlobalDomains(): array;

    public function getActiveDomain(string $language): ?string;

    public function getActiveLanguages(?string $base_domain = null): array;

    public function isGlobalDomain(string $domain): bool;

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

    public function removeLanguageFromURI(?string $uri): string;
}
