<?php


namespace NovemBit\i18n\component\localization\regions;


use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

class Regions extends Component
{
    public $all;

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
     * @param string      $key
     * @param string      $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public function getRegion(
        string $key,
        string $by,
        ?string $return
    ) {
        return Arrays::find($this->all, $key, $by, $return);
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
}