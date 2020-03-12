<?php

namespace NovemBit\i18n\system\helpers;

class Regions extends LocalData
{

    /**
     * @param string $key
     * @param string $by
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
            ['code' => 'af', 'name' => 'Africa', 'languages' => []],
            ['code' => 'na', 'name' => 'North America', 'languages' => []],
            ['code' => 'oc', 'name' => 'Oceania', 'languages' => []],
            ['code' => 'an', 'name' => 'Antarctica', 'languages' => []],
            ['code' => 'as', 'name' => 'Asia', 'languages' => []],
            ['code' => 'eu', 'name' => 'Europe', 'languages' => []],
            ['code' => 'sa', 'name' => 'South America', 'languages' => []],
        ];
    }
}
