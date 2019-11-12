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
                call_user_func_array($callback,[$key,&$val,$_route]);

//                $callback($key, $val, $_route);
            }
        }
    }
}