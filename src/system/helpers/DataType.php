<?php
/**
 * Data type helper class
 * php version 7.2.10
 *
 * @category System\Helpers
 * @package  System\Helpers
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\system\helpers;

/**
 * Helper class for determine content types
 *
 * @category System\Helpers
 * @package  System\Helpers
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class DataType
{
    const UNDEFINED = 0;

    /**
     * Check if string is HTML
     *
     * @param string $string HTML string
     *
     * @return bool
     */
    public static function isHTML($string)
    {
        return $string != strip_tags($string);
    }

    /**
     * Check if string is URL
     *
     * @param string $string URL string
     *
     * @return mixed
     */
    public static function isURL($string)
    {
        return filter_var($string, FILTER_VALIDATE_URL);
    }

    /**
     * Check if string is JSON
     *
     * @param string $string JSON string
     *
     * @return bool
     */
    public static function isJSON($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Get type of string
     *  URL, JSON, HTML
     *
     * @param string $string  String content
     * @param int    $default Default type returning when type is unknown
     *
     * @return int
     */
    public static function getType($string, $default = self::UNDEFINED)
    {
        if (self::isURL($string)) {
            return \NovemBit\i18n\component\translation\type\URL::NAME;
        } elseif (self::isJSON($string)) {
            return \NovemBit\i18n\component\translation\type\JSON::NAME;
        } elseif (self::isHTML($string)) {
            return \NovemBit\i18n\component\translation\type\HTML::NAME;
        } else {
            return $default;
        }
    }

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
            $pos = strpos($before, $after);
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
