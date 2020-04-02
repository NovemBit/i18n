<?php


namespace NovemBit\i18n\system\helpers;


abstract class LocalData
{

    /**
     * @param string $key
     * @param string $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public static function get(
        string $key,
        string $by,
        ?string $return
    ) {
        $data = static::getData();
        foreach ($data as $item) {
            if (isset($item[$by])
                && is_string($item[$by])
                && $item[$by] == strtolower($key)
            ) {
                if ($return != null) {
                    return $item[$return] ?? null;
                } else {
                    return $item;
                }
            }
        }
        return null;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public static function getMap(string $key, string $value): array
    {
        $data = static::getData();

        $result = [];

        foreach ($data as $item) {
            $result[$item[$key]] = $item[$value];
        }

        return $result;
    }

    /**
     * @return array
     */
    protected static function getData(): array
    {
        return [];
    }

}
