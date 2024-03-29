<?php

/**
 * Localization component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\localization;

use JetBrains\PhpStorm\Pure;
use NovemBit\i18n\component\localization\countries\Countries;
use NovemBit\i18n\component\localization\exceptions\LanguageException;
use NovemBit\i18n\component\localization\languages\Languages;
use NovemBit\i18n\component\localization\regions\Regions;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\i18n\system\helpers\URL;

/**
 * Class Localization
 * @package NovemBit\i18n\component\localization
 */
class Localization implements interfaces\Localization
{

    private bool $is_ssl = false;

    /**
     * Main content language
     * */
    private string $from_language = 'en';

    /**
     * Accepted languages
     *
     * @var string[]
     * */
    private array $accept_languages = ['fr', 'it', 'de', 'en'];

    /**
     * Default language
     *
     * @var array[string][string]
     * */
    private array $localization_config = [];

    /**
     * @var array
     * */
    private array $global_domains;

    /**
     * Language query variable key
     *
     * @example https://novembit.com/my/post/url?language=fr
     *
     * @var string
     * */
    private string $language_query_key = 'i18n-language';

    /**
     * Add language code on url path
     *
     * @example https://novembit.com/fr/my/post/url
     *
     * @var bool
     * */
    private bool $language_on_path = true;

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
    private array $path_exclusion_patterns = [];

    /**
     * Current script path in url
     * */
    private static string $script_url;

    /**
     * @var bool
     * */
    private bool $localize_host = true;

    private string $default_http_host;

    private string $prefix = 'i18n';

    public function __construct(
        private Languages $languages,
        private Countries $countries,
        private Regions $regions
    ) {
    }

    /**
     * @return string
     */
    public function getDefaultHttpHost(): string
    {
        return $this->default_http_host;
    }

    public function getConfig(?string $base_domain = null, ?string $value = null): ?array
    {
        $config = [];

        foreach ($this->localization_config as $domain_pattern => $_config) {
            if (preg_match("/$domain_pattern/", $base_domain)) {
                $config = $_config;
                break;
            }
        }

        if ( ! isset($config) && isset($this->localization_config['default'])) {
            $config               = $this->localization_config['default'];
            $config['is_default'] = true;
        }

        if ($value !== null) {
            return $config[$value] ?? null;
        }

        return $config;
    }


    /**
     * @param string|null $base_domain
     * @param bool $assoc
     * @return array
     * @throws exceptions\LanguageException
     */
    public function getActiveLanguages(string $base_domain = null, bool $assoc = false): array
    {
        $base_languages = $this->countries->getActiveLanguages($base_domain);
        $base_languages = $base_languages ?? $this->regions->getActiveLanguages($base_domain) ?? [];

        if (!$assoc) {
            return $base_languages;
        }

        $result = array_flip($base_languages);

        foreach ($result as $key => &$language) {
            $language = $this->languages->getLanguageData($key);
        }

        return $result;
    }

    /**
     * @param  string|null  $base_domain
     * @param  bool  $assoc  include whole data
     *
     * @return array
     * @throws LanguageException
     */
    public function getAcceptLanguages(
        ?string $base_domain = null,
        bool $assoc = false
    ): array {
        $config = $this->getLocalizationConfig($base_domain);

        if (
            isset($config['accept_languages'])
            && !empty($config['accept_languages'])
        ) {
            $accept_languages = $config['accept_languages'];
        } else {
            $accept_languages = $this->accept_languages;
        }

        if (!$assoc) {
            return $accept_languages;
        }

        $result = array_flip($accept_languages);

        foreach ($result as $key => &$language) {
            $language = $this->languages->getLanguageData($key);
        }

        return $result;
    }

    /**
     * @param string $language
     * @return string|null
     */
    public function getActiveDomain(string $language): ?string
    {
        $domain = $this->countries->getByPrimary($language, 'languages', 'domain');
        $domain = $domain ?: $this->regions->getByPrimary($language, 'languages', 'domain');

        return $domain;
    }

    /**
     * @param string $domain
     * @return bool
     */
    #[Pure]
    public function isGlobalDomain(string $domain): bool
    {
        return in_array($domain, $this->getGlobalDomains(), true);
    }

    /**
     * @return mixed
     */
    public function getGlobalDomains(): array
    {
        return $this->global_domains;
    }

    /**
     * Validate one language
     * Check if language exists in `$accepted_languages` array
     *
     * @param  string  $language  language code
     *
     * @return bool
     * @throws LanguageException
     */
    public function validateLanguage(string $language): bool
    {
        if (in_array($language, $this->getAcceptLanguages(false), true)) {
            return true;
        }

        return false;
    }

    /**
     * Validate list of Languages
     * Check if each language code exists on
     * Accepted languages list
     *
     * @param  string[]  $languages  language codes
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
     *
     * @param string|null $url Simple URL
     *
     * @return string|null
     * @throws LanguageException
     */
    public function getLanguageFromUrl(string $url): ?string
    {
        $language = $this->getLanguageFromUrlQuery($url);

        if ($language === null && $this->language_on_path) {
            $language = $this->getLanguageFromUrlPath($url);
        }

        return $language;
    }

    /**
     * Getting language code from url query string
     *
     * @param string $url simple url
     *
     * @return string|null
     * @throws LanguageException
     */
    private function getLanguageFromUrlQuery(string $url): ?string
    {
        $parts = parse_url($url);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            if (
                isset($query[$this->language_query_key])
                && $this->validateLanguage($query[$this->language_query_key])
            ) {
                return $query[$this->language_query_key];
            }
        }

        return null;
    }

    /**
     * Get script url
     * F.e. /path/to/my/dir/index.php or /path/to/my/dir
     *
     * @return string|null
     */
    private static function getScriptUrl(): ?string
    {
        if (isset(self::$script_url)) {
            return self::$script_url;
        }

        $request_uri = Environment::server('REQUEST_URI');
        $script_name = Environment::server('SCRIPT_NAME');

        if ($request_uri === null || $script_name === null) {
            return null;
        }

        if (str_starts_with($request_uri, $script_name)) {
            $str = $script_name;
        } else {
            $paths = explode('/', $script_name);

            unset($paths[count($paths) - 1]);
            $str = implode('/', $paths);
        }

        $str = trim($str, '/');

        self::$script_url = $str;

        return self::$script_url;
    }

    /**
     * @param string $url Simple url
     * @return string
     */
    public function removeScriptNameFromUrl(string $url): string
    {
        $url = ltrim($url, '/ ');
        $url = (string) preg_replace(
            '/^' . preg_quote(self::getScriptUrl(), '/') . '/',
            '',
            $url
        );
        $url = ltrim($url, '/ ');

        foreach ($this->path_exclusion_patterns as $pattern) {
            $url = (string) preg_replace("/$pattern/", '', $url);
        }

        $url = trim($url, '/ ');

        return $url;
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
    private function getLanguageFromUrlPath(string $url): ?string
    {
        $url = $this->removeScriptNameFromUrl($url);

        $uri_parts = parse_url($url);

        if (!isset($uri_parts['path'])) {
            return null;
        }

        $path = explode('/', trim($uri_parts['path'], '/'));

        if (isset($path[0]) && $this->validateLanguage($path[0])) {
            return $path[0];
        }

        return null;
    }

    /**
     * @param string|null $base_domain Base domain
     * @return array
     */
    public function getLocalizationConfig(?string $base_domain = null): array
    {
        return $this->getConfig($base_domain);
    }

    /**
     * @param $parts
     * @param $language
     */
    private function addLanguageToPath(&$parts, $language): void
    {
        $path_parts = explode('/', $parts['path']);
        $path_parts = array_filter($path_parts);

        if (
            (!empty($path_parts) || !empty($parts['query']))
            || (empty($path_parts) && !isset($parts['fragment']))
        ) {
            array_unshift($path_parts, $language);
            $parts['path'] = '/' . implode('/', $path_parts);
        }
    }

    /**
     * Remove language from uri string
     *
     * @param  string|null  $uri  Referenced variable of URI string
     *
     * @return string
     * @throws LanguageException
     */
    public function removeLanguageFromURI(?string $uri): string
    {
        $parts = parse_url(trim($uri, '/'));
        if (isset($parts['path'])) {
            $path = explode('/', ltrim($parts['path'], '/'));

            if ($this->validateLanguage($path[0])) {
                unset($path[0]);
            }
            $parts['path'] = implode('/', $path);
            $new_url       = URL::buildUrl($parts);
            $uri           = empty($new_url) ? '/' : $new_url;
        }

        return $uri;
    }


    /**
     * Adding language code to
     * Already translated URL
     *
     * If `$language_on_path` is true then adding
     * Language code to beginning of url path
     *
     * If `$language_on_path` is false or url contains
     * Script name or directory path then adding only
     * Query parameter of language
     *
     * @param  string  $url  Simple url
     * @param  string  $language  language code
     * @param  string|null  $base_domain  Base domain name
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

        if (
            isset($base_domain)
            && !isset($this->getLocalizationConfig($base_domain)['is_default'])
        ) {
            $parts = parse_url($url);

            if ($this->localize_host && !in_array($base_domain, $this->getGlobalDomains(), true)) {
                /**
                 * Get current base domain active languages
                 * */
                $base_languages = $this->getActiveLanguages($base_domain);

                if (!in_array($language, $base_languages, true)) {
                    $domain = $this->getActiveDomain($language);

                    if (empty($domain)) {
                        $country_regions = $this->countries->getActiveRegions($base_domain) ?? [];
                        foreach ($country_regions as $country_region) {
                            $region_languages = $this->regions->getLanguages($country_region, 'code') ?? [];
                            if (in_array($language, $region_languages, true)) {
                                $domain = $this->regions->getByPrimary($country_region, 'code', 'domain');
                            }
                        }
                    }
                    $base_domain   = $domain ?: $this->getGlobalDomains()[0] ?: $base_domain;
                    $parts['host'] = $domain ?: $this->getGlobalDomains()[0] ?? null;
                }
            }

            /**
             * Change host of url
             * */
            if (isset($parts['host'])) {
                $parts['host'] = $base_domain;
            }

            /**
             * Normalize scheme
             * */
            if (!empty($parts['host']) && empty($parts['scheme'])) {
                $scheme          = $this->is_ssl ? 'https' : 'http';
                $parts['scheme'] = $scheme;
            }

            if ($this->getDefaultLanguage($base_domain) !== $language) {
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
                }

                $this->addLanguageToPath($parts, $language);
            }

            $url = URL::buildUrl($parts);
        } elseif (
            $this->language_on_path === true
            && trim($url, '/') === $this->removeScriptNameFromUrl($url)
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

            $this->addLanguageToPath($parts, $language);

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
     * @return mixed
     */
    public function getFromLanguage(): string
    {
        return $this->from_language;
    }

    /**
     * @param string|null $base_domain Base domain name
     *                                 (usually $_SERVER['HTTP_HOST'])
     * @return string
     * @throws LanguageException
     */
    public function getDefaultLanguage(?string $base_domain = null): string
    {
        $base_domain = $base_domain ?? $this->default_http_host;

        $language = $this->countries->getActiveLanguages($base_domain)[0] ?? null;

        $language = $language ?? $this->regions->getActiveLanguages($base_domain)[0] ?? null;

        $config = $this->getLocalizationConfig($base_domain);

        $language = $language ?? $config['language'] ?? $this->getFromLanguage();

        if ($this->validateLanguage($language)) {
            return $language;
        }

        throw new LanguageException('Invalid default language parameter "' . $language . '"');
    }

    /**
     * @return string
     */
    public function getLanguageQueryKey(): string
    {
        return $this->language_query_key;
    }

    /**
     *
     * @param  string  $from_language  From language code
     *
     * @return Localization
     * @throws LanguageException
     */
    public function setFromLanguage(string $from_language): self
    {
        if ( ! $this->validateLanguage($from_language)) {
            throw new LanguageException('Unknown from language parameter.');
        }

        $this->from_language = $from_language;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param  string[]  $accept_languages
     *
     * @return Localization
     */
    public function setAcceptLanguages(array $accept_languages): Localization
    {
        $this->accept_languages = $accept_languages;

        return $this;
    }

    /**
     * @param  array  $global_domains
     *
     * @return Localization
     */
    public function setGlobalDomains(array $global_domains): Localization
    {
        $this->global_domains = $global_domains;

        return $this;
    }

    /**
     * @param  string  $language_query_key
     *
     * @return Localization
     */
    public function setLanguageQueryKey(string $language_query_key): Localization
    {
        $this->language_query_key = $language_query_key;

        return $this;
    }

    /**
     * @param  string  $default_http_host
     *
     * @return Localization
     */
    public function setDefaultHttpHost(string $default_http_host): Localization
    {
        $this->default_http_host = $default_http_host;

        return $this;
    }

    /**
     * @param  string[]  $path_exclusion_patterns
     *
     * @return Localization
     */
    public function setPathExclusionPatterns(array $path_exclusion_patterns): Localization
    {
        $this->path_exclusion_patterns = $path_exclusion_patterns;

        return $this;
    }

    /**
     * @param  bool  $language_on_path
     *
     * @return Localization
     */
    public function setLanguageOnPath(bool $language_on_path): Localization
    {
        $this->language_on_path = $language_on_path;

        return $this;
    }

    /**
     * @param  array  $localization_config
     *
     * @return Localization
     */
    public function setLocalizationConfig(array $localization_config): Localization
    {
        $this->localization_config = $localization_config;

        return $this;
    }

    /**
     * @param  bool  $localize_host
     *
     * @return Localization
     */
    public function setLocalizeHost(bool $localize_host): Localization
    {
        $this->localize_host = $localize_host;

        return $this;
    }

    /**
     * @param  bool  $is_ssl
     *
     * @return Localization
     */
    public function setIsSsl(bool $is_ssl): Localization
    {
        $this->is_ssl = $is_ssl;

        return $this;
    }
}
