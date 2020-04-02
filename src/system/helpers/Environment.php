<?php

namespace NovemBit\i18n\system\helpers;

/**
 * Class Environment
 * @package NovemBit\i18n\system\helpers
 */
class Environment
{

    /**
     * @param string|null $var
     * @param null $val
     * @return array|mixed|null
     */
    public static function server(?string $var = null, $val = null)
    {
        if ($var === null) {
            return $_SERVER ?? null;
        }
        if ($val === null) {
            return $_SERVER[$var] ?? null;
        }

        $_SERVER[$var] = $val;
        return null;
    }

    /**
     * @param string|null $var
     * @param null $val
     * @return array|mixed|null
     */
    public static function get(?string $var = null, $val = null)
    {
        if ($var === null) {
            return $_GET ?? null;
        }
        if ($val === null) {
            return $_GET[$var] ?? null;
        }
        $_GET[$var] = $val;
        
        return $_GET[$var] ?? null;
    }

    /**
     * @param string|null $var
     * @param null $val
     * @return array|mixed|null
     */
    public static function post(?string $var = null, $val = null)
    {
        if ($var === null) {
            return $_POST ?? null;
        }
        if ($val === null) {
            return $_POST[$var] ?? null;
        }
        $_POST[$var] = $val;

        return $_POST[$var] ?? null;
    }

    /**
     * @param $global
     * @param string|null $var
     * @param null $val
     * @return mixed|null
     */
    public static function global(array &$global, ?string $var = null, $val = null)
    {
        if ($var === null) {
            return $global ?? null;
        }
        if ($val === null) {
            return $global[$var] ?? null;
        }
        $global[$var] = $val;

        return $global[$var] ?? null;
    }
}
