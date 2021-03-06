<?php

namespace NovemBit\i18n\component\localization\countries\interfaces;

use NovemBit\i18n\component\localization\interfaces\LocalizationType;

interface Countries extends LocalizationType
{
    public function getActiveLanguages(?string $base_domain = null): ?array;
    /**
     * Get default country
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     * */
    public function getDefaultCountry(?string $base_domain = null): ?string;
}
