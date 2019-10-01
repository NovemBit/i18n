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

namespace NovemBit\i18n\component;

use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\system\helpers\URL;

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
class Languages extends Component
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
    public $default_language;

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
     * */
    public $language_on_path = true;

    /**
     * If @default_domain parameter is array and contain
     * Host names with specific language
     * Then take language from domain name
     * */
    public $language_on_domain = true;

    /**
     * Language query variable key
     *
     * @example https://novembit.com/my/post/url?language=fr
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
     * */
    public $path_exclusion_patterns = [];

    /**
     * Current script path in url
     * */
    private static $_script_url;

    /**
     * ISO 639-1 languages list
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
        'ca' => 'Catalan, Valencian',
        'km' => 'Central Khmer',
        'ch' => 'Chamorro',
        'ce' => 'Chechen',
        'ny' => 'Chichewa, Chewa, Nyanja',
        'zh' => 'Chinese',
        'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
        'cv' => 'Chuvash',
        'kw' => 'Cornish',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'dv' => 'Divehi, Dhivehi, Maldivian',
        'nl' => 'Dutch, Flemish',
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
        'gd' => 'Gaelic, Scottish Gaelic',
        'gl' => 'Galician',
        'lg' => 'Ganda',
        'ka' => 'Georgian',
        'de' => 'German',
        'ki' => 'Gikuyu, Kikuyu',
        'el' => 'Greek (Modern)',
        'kl' => 'Greenlandic, Kalaallisut',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ht' => 'Haitian, Haitian Creole',
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
        'ia' => 'Interlingua (International Auxiliary Language Association)',
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
        'kj' => 'Kwanyama, Kuanyama',
        'ku' => 'Kurdish',
        'ky' => 'Kyrgyz',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'lb' => 'Letzeburgesch, Luxembourgish',
        'li' => 'Limburgish, Limburgan, Limburger',
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
        'ro' => 'Moldovan, Moldavian, Romanian',
        'mn' => 'Mongolian',
        'na' => 'Nauru',
        'nv' => 'Navajo, Navaho',
        'nd' => 'Northern Ndebele',
        'ng' => 'Ndonga',
        'ne' => 'Nepali',
        'se' => 'Northern Sami',
        'no' => 'Norwegian',
        'nb' => 'Norwegian Bokmål',
        'nn' => 'Norwegian Nynorsk',
        'ii' => 'Nuosu, Sichuan Yi',
        'oc' => 'Occitan (post 1500)',
        'oj' => 'Ojibwa',
        'or' => 'Oriya',
        'om' => 'Oromo',
        'os' => 'Ossetian, Ossetic',
        'pi' => 'Pali',
        'pa' => 'Panjabi, Punjabi',
        'ps' => 'Pashto, Pushto',
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
        'si' => 'Sinhala, Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'so' => 'Somali',
        'st' => 'Sotho, Southern',
        'nr' => 'South Ndebele',
        'es' => 'Spanish, Castilian',
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
        'ug' => 'Uighur, Uyghur',
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
        'za' => 'Zhuang, Chuang',
        'zu' => 'Zulu'
    ];

    /**
     * Getting language code from url query string
     *
     * @param string $url simple url
     *
     * @return string|null
     */
    private function _getLanguageFromUrlQuery($url)
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
     * @return string
     */
    public function getLanguageFromUrlPath($url)
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
     * @param string $url Simple URL
     *
     * @return string|null
     */
    public function getLanguageFromUrl($url)
    {
        $language = $this->_getLanguageFromUrlQuery($url);

        if ($language == null && $this->language_on_path) {
            $language = $this->getLanguageFromUrlPath($url);
        }

        return $language;
    }

    /**
     * Remove executable file from url path
     *
     * @param string $url Simple url
     *
     * @return mixed|string
     */
    public function removeScriptNameFromUrl($url)
    {
        $url = ltrim($url, '/ ');
        $url = preg_replace(
            "/^" . preg_quote($this->getScriptUrl(), '/') . "/",
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
     * If @language_on_path is true then adding
     * Language code to beginning of @URL path
     *
     * If @language_on_path is false or @URL contains
     * Script name or directory path then adding only
     * Query parameter of language
     *
     * @param string $url      Simple url
     * @param string $language language code
     *
     * @return bool|mixed|string
     * @throws Exception
     */
    public function addLanguageToUrl($url, $language)
    {

        /**
         * Make sure @language is valid
         * */
        if (!$this->validateLanguage($language)) {
            return false;
        }

        /**
         * If language_on_domain is enabled and current host exists on
         * "default_language" array and if current language
         * equal $language then change domain name to current host
         * */
        if ($this->language_on_domain
            && isset($this->default_language[$_SERVER['HTTP_HOST']])
            && $this->getDefaultLanguage() == $language
        ) {
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                $parts['host'] = $_SERVER['HTTP_HOST'];
                $url = URL::buildUrl($parts);
            }
        } elseif ($this->language_on_path == true
            && trim($url, '/') == $this->removeScriptNameFromUrl($url)
        ) {
            /**
             * Add language code to url path
             * If @language_on_path is true
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
             * Adding query @language variable
             * */
            $url = URL::addQueryVars($url, $this->language_query_key, $language);
        }

        return $url;
    }

    /**
     * Validate one language
     * Check if language exists in @accepted_languages array
     *
     * @param string $language language code
     *
     * @return bool
     */
    public function validateLanguage($language)
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
    public function validateLanguages($languages)
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
     *
     * @return array|null
     */
    public function getAcceptLanguages($with_names = false)
    {

        if (!$with_names) {
            return $this->accept_languages;
        }

        $accept_languages = array_flip($this->accept_languages);

        foreach ($accept_languages as $key => &$language) {
            $language = $this->_languages[$key];
        }

        return $accept_languages;
    }

    /**
     * Get script url
     * F.e. /path/to/my/dir/index.php or /path/to/my/dir
     *
     * @return mixed|string|null
     */
    public static function getScriptUrl()
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
     * @throws Exception
     */
    public function getFromLanguage()
    {
        if ($this->validateLanguage($this->from_language)) {
            return $this->from_language;
        } else {
            throw new Exception('Unknown from language parameter.');
        }
    }

    /**
     * Get default language
     *
     * @return string
     * @throws Exception
     */
    public function getDefaultLanguage(): string
    {

        if ($this->language_on_domain
            && isset($this->default_language[$_SERVER['HTTP_HOST']])
        ) {
            $language = $this->default_language[$_SERVER['HTTP_HOST']];
        } elseif (isset($this->default_language['default'])) {
            $language = $this->default_language['default'];
        } elseif (isset($this->default_language)
            && is_string($this->default_language)
        ) {
            $language = $this->default_language;
        } else {
            return $this->getFromLanguage();
        }

        if ($this->validateLanguage($language)) {
            return $language;
        } else {
            throw new Exception('Unknown default language parameter.');
        }
    }
}