<?php


namespace NovemBit\i18n\system\helpers;

class URL
{

    const HTTP_URL_REPLACE = 1;
    const HTTP_URL_JOIN_PATH = 2;
    const HTTP_URL_JOIN_QUERY = 4;           // Join query strings
    const HTTP_URL_STRIP_USER = 8;           // Strip any user authentication information
    const HTTP_URL_STRIP_PASS = 16;          // Strip any password authentication information
    const HTTP_URL_STRIP_AUTH = 32;          // Strip any authentication information
    const HTTP_URL_STRIP_PORT = 64;          // Strip explicit port numbers
    const HTTP_URL_STRIP_PATH = 128;         // Strip complete path
    const HTTP_URL_STRIP_QUERY = 256;        // Strip query string
    const HTTP_URL_STRIP_FRAGMENT = 512;     // Strip any fragments (#identifier)
    const HTTP_URL_STRIP_ALL = 1024;         // Strip anything but scheme and host

    /**
     * @param $url
     * @param $paramName
     * @param $paramValue
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
     * @param $url
     * @param $paramName
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
     * Un parse URL
     *
     * @param $parts
     *
     * @return string
     */
    public static function buildUrl($parts)
    {
//        return self::http_build_url($parts);

        $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';
        $host = ($parts['host'] ?? '');
        $port = isset($parts['port']) ? (':' . $parts['port']) : '';
        $user = ($parts['user'] ?? '');
        $pass = isset($parts['pass']) ? (':' . $parts['pass']) : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parts['path']) && !empty($parts['path']) ? '/' . ltrim($parts['path'], '/') : '';
        $query = isset($parts['query']) && !empty($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return implode('', [$scheme, $user, $pass, $host, $port, $path, $query, $fragment]);
    }

    public static function http_build_url($url, $parts = array(), $flags = self::HTTP_URL_REPLACE, &$new_url = false)
    {
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ($flags & self::HTTP_URL_STRIP_ALL) {
            $flags |= self::HTTP_URL_STRIP_USER;
            $flags |= self::HTTP_URL_STRIP_PASS;
            $flags |= self::HTTP_URL_STRIP_PORT;
            $flags |= self::HTTP_URL_STRIP_PATH;
            $flags |= self::HTTP_URL_STRIP_QUERY;
            $flags |= self::HTTP_URL_STRIP_FRAGMENT;
        } // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else {
            if ($flags & self::HTTP_URL_STRIP_AUTH) {
                $flags |= self::HTTP_URL_STRIP_USER;
                $flags |= self::HTTP_URL_STRIP_PASS;
            }
        }

        // Parse the original URL
        // - Suggestion by Sayed Ahad Abbas
        //   In case you send a parse_url array as input
        $parse_url = !is_array($url) ? parse_url($url) : $url;

        // Scheme and Host are always replaced
        if (isset($parts['scheme'])) {
            $parse_url['scheme'] = $parts['scheme'];
        }
        if (isset($parts['host'])) {
            $parse_url['host'] = $parts['host'];
        }

        // (If applicable) Replace the original URL with it's new parts
        if ($flags & self::HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key])) {
                    $parse_url[$key] = $parts[$key];
                }
            }
        } else {
            // Join the original URL path with the new path
            if (isset($parts['path']) && ($flags & self::HTTP_URL_JOIN_PATH)) {
                if (isset($parse_url['path'])) {
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']),
                            '/') . '/' . ltrim($parts['path'], '/');
                } else {
                    $parse_url['path'] = $parts['path'];
                }
            }

            // Join the original query string with the new query string
            if (isset($parts['query']) && ($flags & self::HTTP_URL_JOIN_QUERY)) {
                if (isset($parse_url['query'])) {
                    $parse_url['query'] .= '&' . $parts['query'];
                } else {
                    $parse_url['query'] = $parts['query'];
                }
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key) {
            if ($flags & (int)constant(self::class . '::HTTP_URL_STRIP_' . strtoupper($key))) {
                unset($parse_url[$key]);
            }
        }

        $new_url = $parse_url;

        return
            ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
            . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
            . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
            . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
            . ((isset($parse_url['path'])) ? $parse_url['path'] : '')
            . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
            . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '');
    }


}