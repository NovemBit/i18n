<?php
/**
 * Languages component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\languages;

use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\i18n\system\helpers\URL;
use NovemBit\i18n\component\languages\exceptions\LanguageException;

/**
 * Setting default languages
 *  from language - main website content language
 *  default language - default language for request
 *  accept languages - languages list for translations
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Module $context
 * */
class Languages extends Component implements interfaces\Languages
{

    use traits\Languages;
    /**
     * Main content language
     *
     * @var string
     * */
    public $from_language = 'en';

    /**
     * Default language
     *
     * @var array[string][string]
     * */
    public $localization_config;

    /**
     * Accepted languages
     *
     * @var string[]
     * */
    public $accept_languages = ['fr', 'it', 'de'];

    /**
     * Add language code on url path
     *
     * @example https://novembit.com/fr/my/post/url
     *
     * @var bool
     * */
    public $language_on_path = true;

    /**
     * Language query variable key
     *
     * @example https://novembit.com/my/post/url?language=fr
     *
     * @var string
     * */
    public $language_query_key = 'language';

    /**
     * Pattern to exclude paths from url
     *
     * Example to exclude .php file paths
     *  '.*\.php\/',
     *
     * To exclude wp-admin in wordpress
     *  '^wp-admin(\/|$)'
     *
     * @var string[]
     * */
    public $path_exclusion_patterns = [];

    /**
     * Current script path in url
     *
     * @var string
     * */
    private static $_script_url;

    /**
     * Getting language code from url query string
     *
     * @param string $url simple url
     *
     * @return string|null
     * @throws LanguageException
     */
    private function _getLanguageFromUrlQuery(string $url): ?string
    {
        $parts = parse_url($url);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            if (isset($query[$this->language_query_key])
                && $this->validateLanguage($query[$this->language_query_key])
            ) {
                return $query[$this->language_query_key];
            }
        }

        return null;
    }

    /**
     * Get current language from URL path
     * f.e. https://novembit.com/fr/my/post/path
     *
     * {fr} is valid language
     *
     * Getting {fr} from URL then removing them from path
     * After removing changing global REQUEST_URI
     *
     * @param string $url Simple url
     *
     * @return string|null
     * @throws LanguageException
     */
    private function _getLanguageFromUrlPath(string $url): ?string
    {
        $url = $this->removeScriptNameFromUrl($url);

        $uri_parts = parse_url($url);

        if (!isset($uri_parts['path'])) {
            return null;
        }

        $path = explode('/', trim($uri_parts['path'], '/'));

        if (isset($path[0]) && $this->validateLanguage($path[0])) {

            $language = $path[0];

            return $language;

        }

        return null;
    }

    /**
     * Get script url
     * F.e. /path/to/my/dir/index.php or /path/to/my/dir
     *
     * @return string|null
     */
    private static function _getScriptUrl(): ?string
    {

        if (isset(self::$_script_url)) {
            return self::$_script_url;
        }

        $request_uri = Environment::server('REQUEST_URI');
        $script_name = Environment::server('SCRIPT_NAME');

        if ($request_uri === null || $script_name === null) {
            return null;
        }

        if (strpos($request_uri, $script_name) === 0) {
            $str = $script_name;
        } else {
            $paths = explode('/', $script_name);

            unset($paths[count($paths) - 1]);
            $str = implode('/', $paths);
        }

        $str = trim($str, '/');

        self::$_script_url = $str;

        return self::$_script_url;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $url Simple URL
     *
     * @return string|null
     * @throws LanguageException
     */
    public function getLanguageFromUrl(string $url): ?string
    {
        $language = $this->_getLanguageFromUrlQuery($url);

        if ($language == null && $this->language_on_path) {
            $language = $this->_getLanguageFromUrlPath($url);
        }

        return $language;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $url Simple url
     *
     * @return string
     */
    public function removeScriptNameFromUrl(string $url): string
    {
        $url = ltrim($url, '/ ');
        $url = preg_replace(
            "/^" . preg_quote($this->_getScriptUrl(), '/') . "/",
            '',
            $url
        );
        $url = ltrim($url, '/ ');

        foreach ($this->path_exclusion_patterns as $pattern) {
            $url = preg_replace("/$pattern/", '', $url);
        }

        $url = trim($url, '/ ');

        return $url;
    }

    /**
     * {@inheritDoc}
     *
     * @param string      $url         Simple url
     * @param string      $language    language code
     * @param string|null $base_domain Base domain name
     *
     * @return null|string
     * @throws LanguageException
     */
    public function addLanguageToUrl(
        string $url,
        string $language,
        ?string $base_domain = null
    ): ?string {


        /**
         * Make sure language is valid
         * */
        if (!$this->validateLanguage($language)) {
            return null;
        }

        /**
         * # Notice
         * > This is hard logic.
         * > Notice Dont change this code if you not fully understanding method
         * */
        if (isset($base_domain)
            && !isset($this->getDefaultConfig($base_domain)['is_default'])
        ) {

            $parts = parse_url($url);
            /**
             * Change host of url
             * */
            if (isset($parts['host'])) {
                $parts['host'] = $base_domain;
            }

            if ($this->getDefaultLanguage($base_domain) != $language) {
                if (!isset($parts['path'])) {
                    $parts['path'] = '';
                }

                $exclude = false;
                foreach ($this->path_exclusion_patterns as $pattern) {
                    if (preg_match("/$pattern/", $parts['path'])) {
                        $exclude = true;
                        break;
                    }
                }

                if ($exclude) {
                    return URL::addQueryVars(
                        $url,
                        $this->language_query_key,
                        $language
                    );
                } else {
                    $path_parts = explode('/', $parts['path']);
                    $path_parts = array_filter($path_parts);

                    if ((!empty($path_parts) || !empty($parts['query']))
                        || (empty($path_parts) && !isset($parts['fragment']))
                    ) {
                        array_unshift($path_parts, $language);
                        $parts['path'] = '/' . implode('/', $path_parts);
                    }
                }

            }

            $url = URL::buildUrl($parts);

        } elseif ($this->language_on_path == true
            && trim($url, '/') == $this->removeScriptNameFromUrl($url)
        ) {
            /**
             * Add language code to url path
             * If $language_on_path is true
             * */
            $url = $this->removeScriptNameFromUrl($url);

            $parts = parse_url($url);

            if (!isset($parts['path'])) {
                $parts['path'] = '';
            }

            $path_parts = explode('/', $parts['path']);
            $path_parts = array_filter($path_parts);

            if ((!empty($path_parts) || !empty($parts['query']))
                || (empty($path_parts) && !isset($parts['fragment']))
            ) {
                array_unshift($path_parts, $language);
                $parts['path'] = '/' . implode('/', $path_parts);
            }

            $url = URL::buildUrl($parts);

        } else {
            /**
             * Adding query language variable
             * */
            $url = URL::addQueryVars($url, $this->language_query_key, $language);
        }

        return $url;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $language language code
     *
     * @return bool
     * @throws LanguageException
     */
    public function validateLanguage(string $language): bool
    {
        if (in_array($language, $this->getAcceptLanguages(false))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string[] $languages language codes
     *
     * @return bool
     * @throws LanguageException
     */
    public function validateLanguages(array $languages): bool
    {
        foreach ($languages as $language) {
            if (!$this->validateLanguage($language)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param bool $assoc include whole data
     *
     * @return array|null
     * @throws LanguageException
     */
    public function getAcceptLanguages(
        bool $assoc = false
    ): array {

        if (!$assoc) {
            return $this->accept_languages;
        }

        $accept_languages = array_flip($this->accept_languages);

        foreach ($accept_languages as $key => &$language) {
            $language = [
                'name' => $this->getLanguageNameByCode($key),
                'flag' => $this->getLanguageFlagByCode($key),
                'native' => $this->getLanguageNativeNameByCode($key),
            ];
        }

        return $accept_languages;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getFromLanguage(): string
    {
        return $this->from_language;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return array
     */
    public function getDefaultConfig(?string $base_domain = null): array
    {
        $config = [];

        foreach ($this->localization_config as $domain_pattern => $_config) {
            if (preg_match("/$domain_pattern/", $base_domain)) {
                $config = $_config;
                break;
            }
        }
        if (!isset($config) && isset($this->localization_config['default'])) {
            $config = $this->localization_config['default'];
            $config['is_default'] = true;
        }

        return $config;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain name
     *                                 (usually $_SERVER['HTTP_HOST'])
     *
     * @return string
     * @throws LanguageException
     */
    public function getDefaultLanguage(?string $base_domain = null): string
    {
        $config = $this->getDefaultConfig($base_domain);

        if (isset($config['language'])) {
            $language = $config['language'];
        } else {
            $language = $this->getFromLanguage();
        }

        if ($this->validateLanguage($language)) {
            return $language;
        } else {
            throw new LanguageException('Invalid default language parameter.');
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function getDefaultCountry(?string $base_domain = null): ?string
    {
        $config = $this->getDefaultConfig($base_domain);
        return $config['country'] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return string
     */
    public function getDefaultRegion(?string $base_domain = null): ?string
    {
        $config = $this->getDefaultConfig($base_domain);
        return $config['region'] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getLanguageQueryKey(): string
    {
        return $this->language_query_key;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $from_language From language code
     *
     * @return void
     * @throws LanguageException
     */
    public function setFromLanguage(string $from_language): void
    {
        if ($this->validateLanguage($from_language)) {
            $this->from_language = $from_language;
        } else {
            throw new LanguageException('Unknown from language parameter.');
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     *
     * @return mixed|null
     * @throws LanguageException
     */
    public function getLanguageNameByCode(string $code): string
    {

        $name = self::_getLanguage($code, 'alpha1', 'name') ?? null;

        if ($name === null) {
            throw new LanguageException("Language name property not found!");
        }
        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     *
     * @return mixed|null
     * @throws LanguageException
     */
    public function getLanguageNativeNameByCode(string $code): string
    {
        $name = self::_getLanguage($code, 'alpha1', 'native') ?? null;

        if ($name === null) {
            throw new LanguageException("Language native property not found!");
        }
        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code      Language code
     * @param bool   $rectangle Rectangle format image
     * @param bool   $html      Return html <img src="..
     *
     * @return string
     * @throws LanguageException
     */
    public function getLanguageFlagByCode(
        string $code,
        $rectangle = true,
        $html = false
    ): string {

        $flag = self::_getLanguage($code, 'alpha1', 'flag') ?? null;

        if ($flag === null) {
            throw new LanguageException("Language flag property not found!");
        }

        $name = $this->getLanguageNameByCode($code);

        $size = $rectangle ? "4x3" : "1x1";
        $path = __DIR__ . '/assets/images/flags/' . $size . '/' . $flag . '.svg';

        $base64 = sprintf(
            "data:image/svg+xml;base64,%s",
            base64_encode(file_get_contents($path))
        );

        return $html
            ? "<img alt=\"$name\" title=\"$name\" src=\"$base64\"/>"
            : $base64;
    }
}