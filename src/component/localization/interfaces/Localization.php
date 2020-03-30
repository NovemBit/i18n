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
}
