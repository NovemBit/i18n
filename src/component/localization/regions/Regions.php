<?php

namespace NovemBit\i18n\component\localization\regions;

use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\localization\LocalizationType;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

/**
 * @property Localization $context
 * */
class Regions extends LocalizationType implements interfaces\Regions
{

    /**
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [
            'all' => \NovemBit\i18n\system\helpers\Regions::getData()
        ];
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
     * @param string|null $base_domain
     * @return array|mixed|null
     */
    public function getConfig(?string $base_domain = null, ?string $value = null)
    {
        return $this->getByPrimary($base_domain, 'domain', $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function getDefaultRegion(?string $base_domain = null): ?string
    {
        $config = $this->getConfig($base_domain);
        return $config['name'] ?? null;
    }
}
