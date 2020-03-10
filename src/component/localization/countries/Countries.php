<?php


namespace NovemBit\i18n\component\localization\countries;


use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

class Countries extends Component implements interfaces\Countries
{
    public $all_countries;

    public static function defaultConfig(): array
    {
        return [
            'all_countries' => \NovemBit\i18n\system\helpers\Countries::getData()
        ];
    }

    /**
     * @param string      $key
     * @param string      $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public function getCountry(
        string $key,
        string $by,
        ?string $return
    ) {
        return Arrays::find($this->all_countries, $key, $by, $return);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function getCountriesMap(string $key, string $value): array
    {
        return Arrays::map($this->all_countries, $key, $value);
    }
}
