<?php

namespace NovemBit\i18n\system\helpers;

class Arrays
{

    /**
     * Find from array
     *
     * @param array $data Data
     * @param string $key Key
     * @param string $by By
     * @param string|null $return Return
     *
     * @return mixed|null
     * @deprecated
     */
    public static function find(
        array $data,
        string $key,
        string $by,
        ?string $return
    ) {
        foreach ($data as $item) {
            if (
                isset($item[$by])
                && is_string($item[$by])
                && $item[$by] === $key
            ) {
                if ($return !== null) {
                    return $item[$return] ?? null;
                }

                return $item;
            }
        }
        return null;
    }

    /**
     * Find one element from array with custom logic
     *
     * @param array $data
     * @param string $key
     * @param string $by
     * @param string|null $return
     * @param callable $callback
     * @return mixed|null
     */
    public static function ufind(array $data, string $key, string $by, ?string $return, callable $callback)
    {
        foreach ($data as $item) {
            if (
                isset($item[$by])
                && $callback($key, $item[$by])
            ) {
                if ($return !== null) {
                    return $item[$return] ?? null;
                }

                return $item;
            }
        }
        return null;
    }

    /**
     * Find all elements from array with custom logic
     *
     * @param array $data
     * @param string $key
     * @param string $by
     * @param string|null $return
     * @param callable $callback
     *
     * @return array
     */
    public static function ufindAll(array $data, string $key, string $by, ?string $return, callable $callback)
    {
        $result = [];
        foreach ($data as $item) {
            if (
                isset($item[$by])
                && $callback($key, $item[$by])
            ) {
                if ($return !== null) {
                    $result[] = $item[$return] ?? null;
                } else {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public static function map(array $data, string $key, string $value): array
    {
        $result = [];

        foreach ($data as $item) {
            $result[$item[$key]] = $item[$value];
        }

        return $result;
    }

    /**
     * Recursive array walk with callback and route
     *
     * @param array $arr Main array
     * @param callable $callback Callback function with 3 params (key/val/route)
     * @param string $route Parent route
     * @param string $separator Route separator
     *
     * @return void
     */
    public static function arrayWalkWithRoute(
        array &$arr,
        callable $callback,
        string $route = '',
        string $separator = '>'
    ): void {
        foreach ($arr as $key => &$val) {
            $_route = $route === '' ? $key : $route . $separator . $key;
            if (is_array($val)) {
                self::arrayWalkWithRoute($val, $callback, $_route, $separator);
            } else {
                call_user_func_array($callback, [$key, &$val, $_route]);
            }
        }
    }

    /**
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * array_merge_recursive does indeed merge arrays,
     * but it converts values with duplicate
     * keys to arrays rather than overwriting the value
     * in the first array with the duplicate
     * value in the second array,
     * as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @param bool $only_assoc
     * @return array
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author aaron.yor@gmail.com
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2, $only_assoc = true): array
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (
                is_array($value) &&
                isset($merged [$key]) &&
                is_array($merged [$key]) &&
                ($only_assoc && self::isAssoc($value))
            ) {
                $merged [$key] = self::arrayMergeRecursiveDistinct($merged [$key], $value, $only_assoc);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}
