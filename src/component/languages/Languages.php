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
     * ISO 639-1 languages list
     *
     * @var array
     * */
    private $_languages = [
        'ab' => 'Abkhazian',
        'aa' => 'Afar',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'sq' => 'Albanian',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'an' => 'Aragonese',
        'hy' => 'Armenian',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ae' => 'Avestan',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'bm' => 'Bambara',
        'ba' => 'Bashkir',
        'eu' => 'Basque',
        'be' => 'Belarusian',
        'bn' => 'Bengali',
        'bh' => 'Bihari languages',
        'bi' => 'Bislama',
        'bs' => 'Bosnian',
        'br' => 'Breton',
        'bg' => 'Bulgarian',
        'my' => 'Burmese',
        'ca' => 'Catalan',
        'km' => 'Central Khmer',
        'ch' => 'Chamorro',
        'ce' => 'Chechen',
        'ny' => 'Chichewa',
        'zh' => 'Chinese',
        'cu' => 'Church Slavonic',
        'cv' => 'Chuvash',
        'kw' => 'Cornish',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'dv' => 'Divehi',
        'nl' => 'Dutch',
        'dz' => 'Dzongkha',
        'en' => 'English',
        'eo' => 'Esperanto',
        'et' => 'Estonian',
        'ee' => 'Ewe',
        'fo' => 'Faroese',
        'fj' => 'Fijian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'ff' => 'Fulah',
        'gd' => 'Gaelic',
        'gl' => 'Galician',
        'lg' => 'Ganda',
        'ka' => 'Georgian',
        'de' => 'German',
        'ki' => 'Gikuyu (Kikuyu)',
        'el' => 'Greek (Modern)',
        'kl' => 'Greenlandic',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ht' => 'Haitian',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hz' => 'Herero',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hu' => 'Hungarian',
        'is' => 'Icelandic',
        'io' => 'Ido',
        'ig' => 'Igbo',
        'id' => 'Indonesian',
        'ia' => 'Interlingua',
        'ie' => 'Interlingue',
        'iu' => 'Inuktitut',
        'ik' => 'Inupiaq',
        'ga' => 'Irish',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'jv' => 'Javanese',
        'kn' => 'Kannada',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'kk' => 'Kazakh',
        'rw' => 'Kinyarwanda',
        'kv' => 'Komi',
        'kg' => 'Kongo',
        'ko' => 'Korean',
        'kj' => 'Kwanyama',
        'ku' => 'Kurdish',
        'ky' => 'Kyrgyz',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'lb' => 'Letzeburgesch',
        'li' => 'Limburgish',
        'ln' => 'Lingala',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'mk' => 'Macedonian',
        'mg' => 'Malagasy',
        'ms' => 'Malay',
        'ml' => 'Malayalam',
        'mt' => 'Maltese',
        'gv' => 'Manx',
        'mi' => 'Maori',
        'mr' => 'Marathi',
        'mh' => 'Marshallese',
        'ro' => 'Romanian',
        'mn' => 'Mongolian',
        'na' => 'Nauru',
        'nv' => 'Navajo',
        'nd' => 'Northern Ndebele',
        'ng' => 'Ndonga',
        'ne' => 'Nepali',
        'se' => 'Northern Sami',
        'no' => 'Norwegian',
        'nb' => 'Norwegian Bokmål',
        'nn' => 'Norwegian Nynorsk',
        'ii' => 'Nuosu',
        'oc' => 'Occitan (post 1500)',
        'oj' => 'Ojibwa',
        'or' => 'Oriya',
        'om' => 'Oromo',
        'os' => 'Ossetian',
        'pi' => 'Pali',
        'pa' => 'Panjabi',
        'ps' => 'Pashto',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'rn' => 'Rundi',
        'ru' => 'Russian',
        'sm' => 'Samoan',
        'sg' => 'Sango',
        'sa' => 'Sanskrit',
        'sc' => 'Sardinian',
        'sr' => 'Serbian',
        'sn' => 'Shona',
        'sd' => 'Sindhi',
        'si' => 'Sinhala',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'so' => 'Somali',
        'st' => 'Sotho',
        'nr' => 'South Ndebele',
        'es' => 'Spanish',
        'su' => 'Sundanese',
        'sw' => 'Swahili',
        'ss' => 'Swati',
        'sv' => 'Swedish',
        'tl' => 'Tagalog',
        'ty' => 'Tahitian',
        'tg' => 'Tajik',
        'ta' => 'Tamil',
        'tt' => 'Tatar',
        'te' => 'Telugu',
        'th' => 'Thai',
        'bo' => 'Tibetan',
        'ti' => 'Tigrinya',
        'to' => 'Tonga (Tonga Islands)',
        'ts' => 'Tsonga',
        'tn' => 'Tswana',
        'tr' => 'Turkish',
        'tk' => 'Turkmen',
        'tw' => 'Twi',
        'ug' => 'Uighur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'Volap_k',
        'wa' => 'Walloon',
        'cy' => 'Welsh',
        'fy' => 'Western Frisian',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang',
        'zu' => 'Zulu'
    ];

    /**
     * Getting language code from url query string
     *
     * @param string $url simple url
     *
     * @return string|null
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
     * Get language code from url
     *
     * @param string|null $url Simple URL
     *
     * @return string|null
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
     * Remove executable file from url path
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
            && isset($this->localization_config[$base_domain])
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
     * Validate one language
     * Check if language exists in `$accepted_languages` array
     *
     * @param string $language language code
     *
     * @return bool
     */
    public function validateLanguage(string $language): bool
    {
        if (in_array($language, $this->accept_languages)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate list of Languages
     * Check if each language code exists on
     * Accepted languages list
     *
     * @param string[] $languages language codes
     *
     * @return bool
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
     * Get accepted languages array
     *
     * @param bool $with_names return languages with assoc keys and names
     * @param bool $with_flags return with flags
     *
     * @return array|null
     */
    public function getAcceptLanguages(
        bool $with_names = false,
        bool $with_flags = false
    ): array {

        if (!$with_names) {
            return $this->accept_languages;
        }

        $accept_languages = array_flip($this->accept_languages);

        foreach ($accept_languages as $key => &$language) {
            $language = [
                'name' => $this->_languages[$key],
                'flag' => $this->getFlagByLanguage($key)
            ];
        }

        return $accept_languages;
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

        if (!isset($_SERVER['REQUEST_URI']) || !isset($_SERVER['SCRIPT_NAME'])) {
            return null;
        }

        $request_uri = $_SERVER['REQUEST_URI'];
        $script_name = $_SERVER['SCRIPT_NAME'];

        if (strpos($request_uri, $script_name) === 0) {
            $str = $script_name;
        } else {
            $paths = explode('/', $_SERVER['SCRIPT_NAME']);

            unset($paths[count($paths) - 1]);
            $str = implode('/', $paths);
        }

        $str = trim($str, '/');

        self::$_script_url = $str;

        return self::$_script_url;
    }

    /**
     * Get main from languages
     *
     * @return mixed
     */
    public function getFromLanguage(): string
    {
        return $this->from_language;
    }

    public function getDefaultLanguageConfig(?string $base_domain = null): array
    {
        if ($base_domain !== null
            && isset($this->localization_config[$base_domain])
        ) {
            return $this->localization_config[$base_domain];
        } elseif (isset($this->localization_config['default'])) {
            return $this->localization_config['default'];
        }
        return [];
    }

    /**
     * Get default language
     *
     * @param string|null $base_domain Base domain name
     *                                 (usually $_SERVER['HTTP_HOST'])
     *
     * @return string
     * @throws LanguageException
     */
    public function getDefaultLanguage(?string $base_domain = null): string
    {
        $config = $this->getDefaultLanguageConfig($base_domain);

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

    public function getDefaultCountry(?string $base_domain = null): string
    {
        $config = $this->getDefaultLanguageConfig($base_domain);
        return $config['country'] ?? "US";
    }

    /**
     * Get language query key
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
     * Get flag of language country
     *
     * @param string $language Language code
     * @param bool   $html     return html <img src="..
     *
     * @return string
     */
    public function getFlagByLanguage(string $language, $html = false): string
    {
        $path = __DIR__ . '/assets/images/flags/4x3/' . $language . '.svg';
        $data = file_get_contents($path);
        $base64 = 'data:image/svg+xml;base64,' . base64_encode($data);
        if ($html) {
            return "<img alt=\"$language\" title=\"$language\" src=\"$base64\"/>";
        } else {
            return $base64;
        }
    }
}