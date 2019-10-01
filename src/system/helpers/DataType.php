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

    const HTML = 'html';
    const JSON = 'json';
    const URL = 'url';
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
     * @param string $string String content
     * @param int $default Default type returning when type is unknown
     *
     * @return int|string
     */
    public static function getType($string, $default = self::UNDEFINED)
    {
        if (self::isURL($string)) {
            return self::URL;
        } elseif (self::isJSON($string)) {
            return self::JSON;
        } elseif (self::isHTML($string)) {
            return self::HTML;
        } else {
            return $default;
        }
    }
}
