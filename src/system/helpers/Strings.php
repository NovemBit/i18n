<?php


namespace NovemBit\i18n\system\helpers;


class Strings
{
    /**
     * Get string difference
     *
     * @param string      $before Initial type of string
     * @param string      $after  Final type of string
     * @param string|null $prefix Referenced variable to receive difference prefix
     * @param string|null $suffix Referenced variable to receive difference suffix
     *
     * @return void
     */
    public static function getStringsDifference(
        $before,
        $after,
        &$prefix = null,
        &$suffix = null
    ) {
        $prefix = $before;
        $suffix = '';

        if ($after != '') {
            $pos = strrpos($before, $after);
            $prefix = substr($before, 0, $pos);
            $suffix = substr(
                $before,
                strlen($prefix) +
                strlen($after),
                strlen($before)
            );
        }

    }
}