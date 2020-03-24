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
    public const DONT_INCLUDE_CHILD_LANGUAGES = 0;
    public const INCLUDE_CHILD_PRIMARY_LANGUAGES = 1;
    public const INCLUDE_CHILD_ALL_LANGUAGES = 2;

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

    /**
     * @param string|null $base_domain
     * @return array|null
     */
    public function getActiveLanguages(?string $base_domain = null): ?array
    {
        return $this->getLanguages($base_domain, 'domain');
    }

    /**
     * @param string|null $base_domain
     * @return array|null
     */
    public function getActiveLanguage(?string $base_domain = null): ?array
    {
        return $this->getActiveLanguages($base_domain)[0] ?? null;
    }

    /**
     * Get Languages
     *
     * @param $key
     * @param string $by
     * @return array|null
     */
    public function getLanguages($key, $by = 'code'): ?array
    {
        
        $region = $this->getByPrimary($key, $by) ?? [];

        $languages = $region['languages'] ?? [];


        $include = $region['include_languages'] ?? self::DONT_INCLUDE_CHILD_LANGUAGES;

        if ($include == self::INCLUDE_CHILD_PRIMARY_LANGUAGES || $include == self::INCLUDE_CHILD_ALL_LANGUAGES) {
            $countries_languages = $this->context->countries->getByPrimary(
                $region['code'],
                'regions',
                'languages',
                true
            ) ?? [];
            foreach ($countries_languages as $country_languages) {
                if ($include == self::INCLUDE_CHILD_PRIMARY_LANGUAGES && isset($country_languages[0])) {
                    $languages[] = $country_languages[0];
                } elseif ($include == self::INCLUDE_CHILD_ALL_LANGUAGES) {
                    $languages = array_merge($languages, $country_languages);
                }
            }
        }
        $languages = array_unique($languages);

        return empty($languages) ? null : $languages;
    }
}
