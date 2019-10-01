<?php
/**
 * Helper Class for working with URLs
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
 * Helper class for some actions with URLs
 *
 * @category System\Helpers
 * @package  System\Helpers
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class URL
{

    /**
     * Adding query parameters to URL
     *
     * @param string $url Initial url
     * @param string $paramName Parameter name (key)
     * @param string $paramValue Value of parameter
     *
     * @return string
     */
    public static function addQueryVars($url, $paramName, $paramValue)
    {

        $url_data = parse_url($url);
        if (!isset($url_data["query"])) {
            $url_data["query"] = "";
        }

        $params = array();
        parse_str($url_data['query'], $params);
        $params[$paramName] = $paramValue;
        $url_data['query'] = http_build_query($params);

        return self::buildUrl($url_data);
    }

    /**
     * Remove Query parameter from URL
     *
     * @param string $url Initial url
     * @param string $paramName Parameter name (key)
     *
     * @return string
     */
    public static function removeQueryVars($url, $paramName)
    {

        $url_data = parse_url($url);
        if (!isset($url_data["query"])) {
            $url_data["query"] = "";
        }

        $params = array();
        parse_str($url_data['query'], $params);

        if (isset($params[$paramName])) {
            unset($params[$paramName]);
        }

        $url_data['query'] = http_build_query($params);

        return self::buildUrl($url_data);
    }

    /**
     * Build url from parts
     * Same as reversed parse_url
     *
     * @param array $parts Parts of url
     *
     * @return string
     */
    public static function buildUrl($parts)
    {

        $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';
        $host = ($parts['host'] ?? '');
        $port = isset($parts['port']) ? (':' . $parts['port']) : '';
        $user = ($parts['user'] ?? '');
        $pass = isset($parts['pass']) ? (':' . $parts['pass']) : '';
        $pass = ($user || $pass) ? "$pass@" : '';

        $path = (isset($parts['path']) &&
            !empty($parts['path'])) ? ('/' . ltrim($parts['path'], '/')) : '';

        $query = (isset($parts['query'])
            && !empty($parts['query'])) ? ('?' . $parts['query']) : '';

        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return implode(
            '',
            [
                $scheme,
                $user,
                $pass,
                $host,
                $port,
                $path,
                $query,
                $fragment
            ]
        );
    }
}