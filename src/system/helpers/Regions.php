<?php


namespace NovemBit\i18n\system\helpers;


class Regions extends LocalData
{

    /**
     * @param string      $key
     * @param string      $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public static function getCountry(
        string $key,
        string $by = 'code',
        ?string $return = 'name'
    ) {
        return self::get($key, $by, $return);
    }

    /**
     * @return array[]
     */
    public static function getData(): array
    {
        return [
            ['code' => 'af', 'name' => 'Africa'],
            ['code' => 'na', 'name' => 'North America'],
            ['code' => 'oc', 'name' => 'Oceania'],
            ['code' => 'an', 'name' => 'Antarctica'],
            ['code' => 'as', 'name' => 'Asia'],
            ['code' => 'eu', 'name' => 'Europe'],
            ['code' => 'sa', 'name' => 'South America'],
        ];
    }

}