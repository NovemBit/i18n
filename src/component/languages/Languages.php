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
        'ab' => ['name' => 'Abkhazian', 'flag' => 'ab'],
        'aa' => ['name' => 'Afar', 'flag' => 'aa'],
        'af' => ['name' => 'Afrikaans', 'flag' => 'af'],
        'ak' => ['name' => 'Akan', 'flag' => 'ak'],
        'sq' => ['name' => 'Albanian', 'flag' => 'sq'],
        'am' => ['name' => 'Amharic', 'flag' => 'am'],
        'ar' => ['name' => 'Arabic', 'flag' => 'ar'],
        'an' => ['name' => 'Aragonese', 'flag' => 'an'],
        'hy' => ['name' => 'Armenian', 'flag' => 'hy'],
        'as' => ['name' => 'Assamese', 'flag' => 'as'],
        'av' => ['name' => 'Avaric', 'flag' => 'av'],
        'ae' => ['name' => 'Avestan', 'flag' => 'ae'],
        'ay' => ['name' => 'Aymara', 'flag' => 'ay'],
        'az' => ['name' => 'Azerbaijani', 'flag' => 'az'],
        'bm' => ['name' => 'Bambara', 'flag' => 'bm'],
        'ba' => ['name' => 'Bashkir', 'flag' => 'ba'],
        'eu' => ['name' => 'Basque', 'flag' => 'eu'],
        'be' => ['name' => 'Belarusian', 'flag' => 'be'],
        'bn' => ['name' => 'Bengali', 'flag' => 'bn'],
        'bh' => ['name' => 'Bihari languages', 'flag' => 'bh'],
        'bi' => ['name' => 'Bislama', 'flag' => 'bi'],
        'bs' => ['name' => 'Bosnian', 'flag' => 'bs'],
        'br' => ['name' => 'Breton', 'flag' => 'br'],
        'bg' => ['name' => 'Bulgarian', 'flag' => 'bg'],
        'my' => ['name' => 'Burmese', 'flag' => 'my'],
        'ca' => ['name' => 'Catalan', 'flag' => 'ca'],
        'km' => ['name' => 'Central Khmer', 'flag' => 'km'],
        'ch' => ['name' => 'Chamorro', 'flag' => 'ch'],
        'ce' => ['name' => 'Chechen', 'flag' => 'ce'],
        'ny' => ['name' => 'Chichewa', 'flag' => 'ny'],
        'zh' => ['name' => 'Chinese', 'flag' => 'zh'],
        'cu' => ['name' => 'Church Slavonic', 'flag' => 'cu'],
        'cv' => ['name' => 'Chuvash', 'flag' => 'cv'],
        'kw' => ['name' => 'Cornish', 'flag' => 'kw'],
        'co' => ['name' => 'Corsican', 'flag' => 'co'],
        'cr' => ['name' => 'Cree', 'flag' => 'cr'],
        'hr' => ['name' => 'Croatian', 'flag' => 'hr'],
        'cs' => ['name' => 'Czech', 'flag' => 'cz'],
        'da' => ['name' => 'Danish', 'flag' => 'dk'],
        'dv' => ['name' => 'Divehi', 'flag' => 'dv'],
        'nl' => ['name' => 'Dutch', 'flag' => 'nl'],
        'dz' => ['name' => 'Dzongkha', 'flag' => 'dz'],
        'en' => ['name' => 'English', 'flag' => 'gb'],
        'eo' => ['name' => 'Esperanto', 'flag' => 'eo'],
        'et' => ['name' => 'Estonian', 'flag' => 'ee'],
        'ee' => ['name' => 'Ewe', 'flag' => 'ee'],
        'fo' => ['name' => 'Faroese', 'flag' => 'fo'],
        'fj' => ['name' => 'Fijian', 'flag' => 'fj'],
        'fi' => ['name' => 'Finnish', 'flag' => 'fi'],
        'fr' => ['name' => 'French', 'flag' => 'fr'],
        'ff' => ['name' => 'Fulah', 'flag' => 'ff'],
        'gd' => ['name' => 'Gaelic', 'flag' => 'gd'],
        'gl' => ['name' => 'Galician', 'flag' => 'gl'],
        'lg' => ['name' => 'Ganda', 'flag' => 'lg'],
        'ka' => ['name' => 'Georgian', 'flag' => 'ka'],
        'de' => ['name' => 'German', 'flag' => 'de'],
        'ki' => ['name' => 'Gikuyu (Kikuyu)', 'flag' => 'ki'],
        'el' => ['name' => 'Greek', 'flag' => 'gr'],
        'kl' => ['name' => 'Greenlandic', 'flag' => 'kl'],
        'gn' => ['name' => 'Guarani', 'flag' => 'gn'],
        'gu' => ['name' => 'Gujarati', 'flag' => 'gu'],
        'ht' => ['name' => 'Haitian', 'flag' => 'ht'],
        'ha' => ['name' => 'Hausa', 'flag' => 'ha'],
        'he' => ['name' => 'Hebrew', 'flag' => 'he'],
        'hz' => ['name' => 'Herero', 'flag' => 'hz'],
        'hi' => ['name' => 'Hindi', 'flag' => 'hi'],
        'ho' => ['name' => 'Hiri Motu', 'flag' => 'ho'],
        'hu' => ['name' => 'Hungarian', 'flag' => 'hu'],
        'is' => ['name' => 'Icelandic', 'flag' => 'is'],
        'io' => ['name' => 'Ido', 'flag' => 'io'],
        'ig' => ['name' => 'Igbo', 'flag' => 'ig'],
        'id' => ['name' => 'Indonesian', 'flag' => 'id'],
        'ia' => ['name' => 'Interlingua', 'flag' => 'ia'],
        'ie' => ['name' => 'Interlingue', 'flag' => 'ie'],
        'iu' => ['name' => 'Inuktitut', 'flag' => 'iu'],
        'ik' => ['name' => 'Inupiaq', 'flag' => 'ik'],
        'ga' => ['name' => 'Irish', 'flag' => 'ga'],
        'it' => ['name' => 'Italian', 'flag' => 'it'],
        'ja' => ['name' => 'Japanese', 'flag' => 'jp'],
        'jv' => ['name' => 'Javanese', 'flag' => 'jv'],
        'kn' => ['name' => 'Kannada', 'flag' => 'kn'],
        'kr' => ['name' => 'Kanuri', 'flag' => 'kr'],
        'ks' => ['name' => 'Kashmiri', 'flag' => 'ks'],
        'kk' => ['name' => 'Kazakh', 'flag' => 'kk'],
        'rw' => ['name' => 'Kinyarwanda', 'flag' => 'rw'],
        'kv' => ['name' => 'Komi', 'flag' => 'kv'],
        'kg' => ['name' => 'Kongo', 'flag' => 'kg'],
        'ko' => ['name' => 'Korean', 'flag' => 'kr'],
        'kj' => ['name' => 'Kwanyama', 'flag' => 'kj'],
        'ku' => ['name' => 'Kurdish', 'flag' => 'ku'],
        'ky' => ['name' => 'Kyrgyz', 'flag' => 'ky'],
        'lo' => ['name' => 'Lao', 'flag' => 'lo'],
        'la' => ['name' => 'Latin', 'flag' => 'la'],
        'lv' => ['name' => 'Latvian', 'flag' => 'lv'],
        'lb' => ['name' => 'Letzeburgesch', 'flag' => 'lb'],
        'li' => ['name' => 'Limburgish', 'flag' => 'li'],
        'ln' => ['name' => 'Lingala', 'flag' => 'ln'],
        'lt' => ['name' => 'Lithuanian', 'flag' => 'lt'],
        'lu' => ['name' => 'Luba-Katanga', 'flag' => 'lu'],
        'mk' => ['name' => 'Macedonian', 'flag' => 'mk'],
        'mg' => ['name' => 'Malagasy', 'flag' => 'mg'],
        'ms' => ['name' => 'Malay', 'flag' => 'ms'],
        'ml' => ['name' => 'Malayalam', 'flag' => 'ml'],
        'mt' => ['name' => 'Maltese', 'flag' => 'mt'],
        'gv' => ['name' => 'Manx', 'flag' => 'gv'],
        'mi' => ['name' => 'Maori', 'flag' => 'mi'],
        'mr' => ['name' => 'Marathi', 'flag' => 'mr'],
        'mh' => ['name' => 'Marshallese', 'flag' => 'mh'],
        'ro' => ['name' => 'Romanian', 'flag' => 'ro'],
        'mn' => ['name' => 'Mongolian', 'flag' => 'mn'],
        'na' => ['name' => 'Nauru', 'flag' => 'na'],
        'nv' => ['name' => 'Navajo', 'flag' => 'nv'],
        'nd' => ['name' => 'Northern Ndebele', 'flag' => 'nd'],
        'ng' => ['name' => 'Ndonga', 'flag' => 'ng'],
        'ne' => ['name' => 'Nepali', 'flag' => 'ne'],
        'se' => ['name' => 'Northern Sami', 'flag' => 'se'],
        'no' => ['name' => 'Norwegian', 'flag' => 'no'],
        'nb' => ['name' => 'Norwegian Bokmål', 'flag' => 'nb'],
        'nn' => ['name' => 'Norwegian Nynorsk', 'flag' => 'nn'],
        'ii' => ['name' => 'Nuosu', 'flag' => 'ii'],
        'oc' => ['name' => 'Occitan (post 1500)', 'flag' => 'oc'],
        'oj' => ['name' => 'Ojibwa', 'flag' => 'oj'],
        'or' => ['name' => 'Oriya', 'flag' => 'or'],
        'om' => ['name' => 'Oromo', 'flag' => 'om'],
        'os' => ['name' => 'Ossetian', 'flag' => 'os'],
        'pi' => ['name' => 'Pali', 'flag' => 'pi'],
        'pa' => ['name' => 'Panjabi', 'flag' => 'pa'],
        'ps' => ['name' => 'Pashto', 'flag' => 'ps'],
        'fa' => ['name' => 'Persian', 'flag' => 'fa'],
        'pl' => ['name' => 'Polish', 'flag' => 'pl'],
        'pt' => ['name' => 'Portuguese', 'flag' => 'pt'],
        'qu' => ['name' => 'Quechua', 'flag' => 'qu'],
        'rm' => ['name' => 'Romansh', 'flag' => 'rm'],
        'rn' => ['name' => 'Rundi', 'flag' => 'rn'],
        'ru' => ['name' => 'Russian', 'flag' => 'ru'],
        'sm' => ['name' => 'Samoan', 'flag' => 'sm'],
        'sg' => ['name' => 'Sango', 'flag' => 'sg'],
        'sa' => ['name' => 'Sanskrit', 'flag' => 'sa'],
        'sc' => ['name' => 'Sardinian', 'flag' => 'sc'],
        'sr' => ['name' => 'Serbian', 'flag' => 'sr'],
        'sn' => ['name' => 'Shona', 'flag' => 'sn'],
        'sd' => ['name' => 'Sindhi', 'flag' => 'sd'],
        'si' => ['name' => 'Sinhala', 'flag' => 'si'],
        'sk' => ['name' => 'Slovak', 'flag' => 'sk'],
        'sl' => ['name' => 'Slovenian', 'flag' => 'si'],
        'so' => ['name' => 'Somali', 'flag' => 'so'],
        'st' => ['name' => 'Sotho', 'flag' => 'st'],
        'nr' => ['name' => 'South Ndebele', 'flag' => 'nr'],
        'es' => ['name' => 'Spanish', 'flag' => 'es'],
        'su' => ['name' => 'Sundanese', 'flag' => 'su'],
        'sw' => ['name' => 'Swahili', 'flag' => 'sw'],
        'ss' => ['name' => 'Swati', 'flag' => 'ss'],
        'sv' => ['name' => 'Swedish', 'flag' => 'sv'],
        'tl' => ['name' => 'Tagalog', 'flag' => 'tl'],
        'ty' => ['name' => 'Tahitian', 'flag' => 'ty'],
        'tg' => ['name' => 'Tajik', 'flag' => 'tg'],
        'ta' => ['name' => 'Tamil', 'flag' => 'ta'],
        'tt' => ['name' => 'Tatar', 'flag' => 'tt'],
        'te' => ['name' => 'Telugu', 'flag' => 'te'],
        'th' => ['name' => 'Thai', 'flag' => 'th'],
        'bo' => ['name' => 'Tibetan', 'flag' => 'bo'],
        'ti' => ['name' => 'Tigrinya', 'flag' => 'ti'],
        'to' => ['name' => 'Tonga (Tonga Islands)', 'flag' => 'to'],
        'ts' => ['name' => 'Tsonga', 'flag' => 'ts'],
        'tn' => ['name' => 'Tswana', 'flag' => 'tn'],
        'tr' => ['name' => 'Turkish', 'flag' => 'tr'],
        'tk' => ['name' => 'Turkmen', 'flag' => 'tk'],
        'tw' => ['name' => 'Twi', 'flag' => 'tw'],
        'ug' => ['name' => 'Uighur', 'flag' => 'ug'],
        'uk' => ['name' => 'Ukrainian', 'flag' => 'uk'],
        'ur' => ['name' => 'Urdu', 'flag' => 'ur'],
        'uz' => ['name' => 'Uzbek', 'flag' => 'uz'],
        've' => ['name' => 'Venda', 'flag' => 've'],
        'vi' => ['name' => 'Vietnamese', 'flag' => 'vi'],
        'vo' => ['name' => 'Volap_k', 'flag' => 'vo'],
        'wa' => ['name' => 'Walloon', 'flag' => 'wa'],
        'cy' => ['name' => 'Welsh', 'flag' => 'cy'],
        'fy' => ['name' => 'Western Frisian', 'flag' => 'fy'],
        'wo' => ['name' => 'Wolof', 'flag' => 'wo'],
        'xh' => ['name' => 'Xhosa', 'flag' => 'xh'],
        'yi' => ['name' => 'Yiddish', 'flag' => 'yi'],
        'yo' => ['name' => 'Yoruba', 'flag' => 'yo'],
        'za' => ['name' => 'Zhuang', 'flag' => 'za'],
        'zu' => ['name' => 'Zulu', 'flag' => 'zu']
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
                'name' => $this->_languages[$key]['name'],
                'flag' => $this->getFlagByLanguage($key)
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

        foreach ($this->localization_config as $domain_pattern => $_config) {
            if (preg_match("/$domain_pattern/", $base_domain)) {
                return $_config;
                break;
            }
        }
        if (!isset($config) && isset($this->localization_config['default'])) {
            return $this->localization_config['default'];
        }

        return [];
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
     * @param string $language Language code
     * @param bool   $html     return html <img src="..
     *
     * @return string
     */
    public function getFlagByLanguage(string $language, $html = false): string
    {
        $flag = $this->_languages[$language]['flag'];
        $name = $this->_languages[$language]['name'];

        $path = __DIR__ . '/assets/images/flags/4x3/' . $flag . '.svg';
        $data = file_get_contents($path);
        $base64 = 'data:image/svg+xml;base64,' . base64_encode($data);
        if ($html) {
            return "<img alt=\"$name\" title=\"$name\" src=\"$base64\"/>";
        } else {
            return $base64;
        }
    }
}