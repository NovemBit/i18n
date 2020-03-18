<?php

namespace NovemBit\i18n\component\localization\countries;

use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\localization\LocalizationType;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

/**
 * @property Localization $context
 * */
class Countries extends LocalizationType implements interfaces\Countries
{
    public static function defaultConfig(): array
    {
        return [
            'all' => \NovemBit\i18n\system\helpers\Countries::getData()
        ];
    }

    /**
     * @param string|null $base_domain
     * @return array|mixed|null
     */
    public function getConfig(string $base_domain = null, ?string $value = null)
    {
        return $this->getByPrimary($base_domain, 'domain', $value);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function getCountriesMap(string $key, string $value): array
    {
        return Arrays::map($this->all, $key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function getDefaultCountry(?string $base_domain = null): ?string
    {
        $config = $this->context->getConfig($base_domain);
        return $config['countries'][0] ?? null;
    }
}
