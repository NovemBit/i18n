<?php


namespace NovemBit\i18n\component\localization\traits;


trait Countries
{

    private static function _getCountry(
        string $key,
        string $by = 'alpha2',
        ?string $return = 'name'
    ) {
        $countries = \NovemBit\i18n\system\helpers\Countries::getCountries();
        foreach ($countries as $country) {
            if (isset($country[$by])
                && is_string($country[$by])
                && $country[$by] == strtoupper($key)
            ) {
                if ($return != null) {
                    return $country[$return] ?? null;
                } else {
                    return $country;
                }
            }
        }
        return null;
    }


}