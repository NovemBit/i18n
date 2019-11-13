<?php


namespace NovemBit\i18n\system\helpers;


class Arrays
{
    /**
     * Recursive array walk with callback and route
     *
     * @param array $arr Main array
     * @param callable $callback Callback function with 3 params (key/val/route)
     * @param Strings $route Parent route
     * @param Strings $separator Route separator
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
            $_route = $route == '' ? $key : $route . $separator . $key;
            if (is_array($val)) {
                self::arrayWalkWithRoute($val, $callback, $_route, $separator);
            } else {
                call_user_func_array($callback, [$key, &$val, $_route]);
            }
        }
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
     * @return array
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::arrayMergeRecursiveDistinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}