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
     * @todo Correct iso-639-1 to iso 3366
     *
     * @var array
     * */
    private $_languages = [
        'ab' => ['name' => 'Abkhazian', 'native' => 'Abkhazian', 'flag' => 'ab'],
        'aa' => ['name' => 'Afar', 'native' => 'Afar', 'flag' => 'aa'],
        'af' => ['name' => 'Afrikaans', 'native' => 'Afrikaans', 'flag' => 'af'],
        'ak' => ['name' => 'Akan', 'native' => 'Akan', 'flag' => 'ak'],
        'sq' => ['name' => 'Albanian', 'native' => 'Albanian', 'flag' => 'sq'],
        'am' => ['name' => 'Amharic', 'native' => 'Amharic', 'flag' => 'am'],
        'ar' => ['name' => 'Arabic', 'native' => 'Arabic', 'flag' => 'ar'],
        'an' => ['name' => 'Aragonese', 'native' => 'Aragonese', 'flag' => 'an'],
        'hy' => ['name' => 'Armenian', 'native' => 'Armenian', 'flag' => 'hy'],
        'as' => ['name' => 'Assamese', 'native' => 'Assamese', 'flag' => 'as'],
        'av' => ['name' => 'Avaric', 'native' => 'Avaric', 'flag' => 'av'],
        'ae' => ['name' => 'Avestan', 'native' => 'Avestan', 'flag' => 'ae'],
        'ay' => ['name' => 'Aymara', 'native' => 'Aymara', 'flag' => 'ay'],
        'az' => ['name' => 'Azerbaijani', 'native' => 'Azerbaijani', 'flag' => 'az'],
        'bm' => ['name' => 'Bambara', 'native' => 'Bambara', 'flag' => 'bm'],
        'ba' => ['name' => 'Bashkir', 'native' => 'Bashkir', 'flag' => 'ba'],
        'eu' => ['name' => 'Basque', 'native' => 'Basque', 'flag' => 'eu'],
        'be' => ['name' => 'Belarusian', 'native' => 'Belarusian', 'flag' => 'be'],
        'bn' => ['name' => 'Bengali', 'native' => 'Bengali', 'flag' => 'bn'],
        'bh' => ['name' => 'Bihari languages', 'native' => 'Bihari languages', 'flag' => 'bh'],
        'bi' => ['name' => 'Bislama', 'native' => 'Bislama', 'flag' => 'bi'],
        'bs' => ['name' => 'Bosnian', 'native' => 'Bosnian', 'flag' => 'bs'],
        'br' => ['name' => 'Breton', 'native' => 'Breton', 'flag' => 'br'],
        'bg' => ['name' => 'Bulgarian', 'native' => 'Bulgarian', 'flag' => 'bg'],
        'my' => ['name' => 'Burmese', 'native' => 'Burmese', 'flag' => 'my'],
        'ca' => ['name' => 'Catalan', 'native' => 'Catalan', 'flag' => 'ca'],
        'km' => ['name' => 'Central Khmer', 'native' => 'Central Khmer', 'flag' => 'km'],
        'ch' => ['name' => 'Chamorro', 'native' => 'Chamorro', 'flag' => 'ch'],
        'ce' => ['name' => 'Chechen', 'native' => 'Chechen', 'flag' => 'ce'],
        'ny' => ['name' => 'Chichewa', 'native' => 'Chichewa', 'flag' => 'ny'],
        'zh' => ['name' => 'Chinese', 'native' => 'Chinese', 'flag' => 'zh'],
        'cu' => ['name' => 'Church Slavonic', 'native' => 'Church Slavonic', 'flag' => 'cu'],
        'cv' => ['name' => 'Chuvash', 'native' => 'Chuvash', 'flag' => 'cv'],
        'kw' => ['name' => 'Cornish', 'native' => 'Cornish', 'flag' => 'kw'],
        'co' => ['name' => 'Corsican', 'native' => 'Corsican', 'flag' => 'co'],
        'cr' => ['name' => 'Cree', 'native' => 'Cree', 'flag' => 'cr'],
        'hr' => ['name' => 'Croatian', 'native' => 'Croatian', 'flag' => 'hr'],
        'cs' => ['name' => 'Czech', 'native' => 'Czech', 'flag' => 'cz'],
        'da' => ['name' => 'Danish', 'native' => 'Danish', 'flag' => 'dk'],
        'dv' => ['name' => 'Divehi', 'native' => 'Divehi', 'flag' => 'dv'],
        'nl' => ['name' => 'Dutch', 'native' => 'Dutch', 'flag' => 'nl'],
        'dz' => ['name' => 'Dzongkha', 'native' => 'Dzongkha', 'flag' => 'dz'],
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => 'gb'],
        'eo' => ['name' => 'Esperanto', 'native' => 'Esperanto', 'flag' => 'eo'],
        'et' => ['name' => 'Estonian', 'native' => 'Estonian', 'flag' => 'ee'],
        'ee' => ['name' => 'Ewe', 'native' => 'Ewe', 'flag' => 'ee'],
        'fo' => ['name' => 'Faroese', 'native' => 'Faroese', 'flag' => 'fo'],
        'fj' => ['name' => 'Fijian', 'native' => 'Fijian', 'flag' => 'fj'],
        'fi' => ['name' => 'Finnish', 'native' => 'Finnish', 'flag' => 'fi'],
        'fr' => ['name' => 'French', 'native' => 'French', 'flag' => 'fr'],
        'ff' => ['name' => 'Fulah', 'native' => 'Fulah', 'flag' => 'ff'],
        'gd' => ['name' => 'Gaelic', 'native' => 'Gaelic', 'flag' => 'gd'],
        'gl' => ['name' => 'Galician', 'native' => 'Galician', 'flag' => 'gl'],
        'lg' => ['name' => 'Ganda', 'native' => 'Ganda', 'flag' => 'lg'],
        'ka' => ['name' => 'Georgian', 'native' => 'Georgian', 'flag' => 'ka'],
        'de' => ['name' => 'German', 'native' => 'German', 'flag' => 'de'],
        'ki' => ['name' => 'Gikuyu (Kikuyu)', 'native' => 'Gikuyu (Kikuyu)', 'flag' => 'ki'],
        'el' => ['name' => 'Greek', 'native' => 'Ελληνικά', 'flag' => 'gr'],
        'kl' => ['name' => 'Greenlandic', 'native' => 'Greenlandic', 'flag' => 'kl'],
        'gn' => ['name' => 'Guarani', 'native' => 'Guarani', 'flag' => 'gn'],
        'gu' => ['name' => 'Gujarati', 'native' => 'Gujarati', 'flag' => 'gu'],
        'ht' => ['name' => 'Haitian', 'native' => 'Haitian', 'flag' => 'ht'],
        'ha' => ['name' => 'Hausa', 'native' => 'Hausa', 'flag' => 'ha'],
        'he' => ['name' => 'Hebrew', 'native' => 'Hebrew', 'flag' => 'he'],
        'hz' => ['name' => 'Herero', 'native' => 'Herero', 'flag' => 'hz'],
        'hi' => ['name' => 'Hindi', 'native' => 'Hindi', 'flag' => 'hi'],
        'ho' => ['name' => 'Hiri Motu', 'native' => 'Hiri Motu', 'flag' => 'ho'],
        'hu' => ['name' => 'Hungarian', 'native' => 'Hungarian', 'flag' => 'hu'],
        'is' => ['name' => 'Icelandic', 'native' => 'Icelandic', 'flag' => 'is'],
        'io' => ['name' => 'Ido', 'native' => 'Ido', 'flag' => 'io'],
        'ig' => ['name' => 'Igbo', 'native' => 'Igbo', 'flag' => 'ig'],
        'id' => ['name' => 'Indonesian', 'native' => 'Indonesian', 'flag' => 'id'],
        'ia' => ['name' => 'Interlingua', 'native' => 'Interlingua', 'flag' => 'ia'],
        'ie' => ['name' => 'Interlingue', 'native' => 'Interlingue', 'flag' => 'ie'],
        'iu' => ['name' => 'Inuktitut', 'native' => 'Inuktitut', 'flag' => 'iu'],
        'ik' => ['name' => 'Inupiaq', 'native' => 'Inupiaq', 'flag' => 'ik'],
        'ga' => ['name' => 'Irish', 'native' => 'Irish', 'flag' => 'ga'],
        'it' => ['name' => 'Italian', 'native' => 'Italian', 'flag' => 'it'],
        'ja' => ['name' => 'Japanese', 'native' => 'Japanese', 'flag' => 'jp'],
        'jv' => ['name' => 'Javanese', 'native' => 'Javanese', 'flag' => 'jv'],
        'kn' => ['name' => 'Kannada', 'native' => 'Kannada', 'flag' => 'kn'],
        'kr' => ['name' => 'Kanuri', 'native' => 'Kanuri', 'flag' => 'kr'],
        'ks' => ['name' => 'Kashmiri', 'native' => 'Kashmiri', 'flag' => 'ks'],
        'kk' => ['name' => 'Kazakh', 'native' => 'Kazakh', 'flag' => 'kk'],
        'rw' => ['name' => 'Kinyarwanda', 'native' => 'Kinyarwanda', 'flag' => 'rw'],
        'kv' => ['name' => 'Komi', 'native' => 'Komi', 'flag' => 'kv'],
        'kg' => ['name' => 'Kongo', 'native' => 'Kongo', 'flag' => 'kg'],
        'ko' => ['name' => 'Korean', 'native' => 'Korean', 'flag' => 'kr'],
        'kj' => ['name' => 'Kwanyama', 'native' => 'Kwanyama', 'flag' => 'kj'],
        'ku' => ['name' => 'Kurdish', 'native' => 'Kurdish', 'flag' => 'ku'],
        'ky' => ['name' => 'Kyrgyz', 'native' => 'Kyrgyz', 'flag' => 'ky'],
        'lo' => ['name' => 'Lao', 'native' => 'Lao', 'flag' => 'lo'],
        'la' => ['name' => 'Latin', 'native' => 'Latin', 'flag' => 'la'],
        'lv' => ['name' => 'Latvian', 'native' => 'Latvian', 'flag' => 'lv'],
        'lb' => ['name' => 'Letzeburgesch', 'native' => 'Letzeburgesch', 'flag' => 'lb'],
        'li' => ['name' => 'Limburgish', 'native' => 'Limburgish', 'flag' => 'li'],
        'ln' => ['name' => 'Lingala', 'native' => 'Lingala', 'flag' => 'ln'],
        'lt' => ['name' => 'Lithuanian', 'native' => 'Lithuanian', 'flag' => 'lt'],
        'lu' => ['name' => 'Luba-Katanga', 'native' => 'Luba-Katanga', 'flag' => 'lu'],
        'mk' => ['name' => 'Macedonian', 'native' => 'Macedonian', 'flag' => 'mk'],
        'mg' => ['name' => 'Malagasy', 'native' => 'Malagasy', 'flag' => 'mg'],
        'ms' => ['name' => 'Malay', 'native' => 'Malay', 'flag' => 'ms'],
        'ml' => ['name' => 'Malayalam', 'native' => 'Malayalam', 'flag' => 'ml'],
        'mt' => ['name' => 'Maltese', 'native' => 'Maltese', 'flag' => 'mt'],
        'gv' => ['name' => 'Manx', 'native' => 'Manx', 'flag' => 'gv'],
        'mi' => ['name' => 'Maori', 'native' => 'Maori', 'flag' => 'mi'],
        'mr' => ['name' => 'Marathi', 'native' => 'Marathi', 'flag' => 'mr'],
        'mh' => ['name' => 'Marshallese', 'native' => 'Marshallese', 'flag' => 'mh'],
        'ro' => ['name' => 'Romanian', 'native' => 'Romanian', 'flag' => 'ro'],
        'mn' => ['name' => 'Mongolian', 'native' => 'Mongolian', 'flag' => 'mn'],
        'na' => ['name' => 'Nauru', 'native' => 'Nauru', 'flag' => 'na'],
        'nv' => ['name' => 'Navajo', 'native' => 'Navajo', 'flag' => 'nv'],
        'nd' => ['name' => 'Northern Ndebele', 'native' => 'Northern Ndebele', 'flag' => 'nd'],
        'ng' => ['name' => 'Ndonga', 'native' => 'Ndonga', 'flag' => 'ng'],
        'ne' => ['name' => 'Nepali', 'native' => 'Nepali', 'flag' => 'ne'],
        'se' => ['name' => 'Northern Sami', 'native' => 'Northern Sami', 'flag' => 'se'],
        'no' => ['name' => 'Norwegian', 'native' => 'Norwegian', 'flag' => 'no'],
        'nb' => ['name' => 'Norwegian Bokmål', 'native' => 'Norwegian Bokmål', 'flag' => 'nb'],
        'nn' => ['name' => 'Norwegian Nynorsk', 'native' => 'Norwegian Nynorsk', 'flag' => 'nn'],
        'ii' => ['name' => 'Nuosu', 'native' => 'Nuosu', 'flag' => 'ii'],
        'oc' => ['name' => 'Occitan (post 1500)', 'native' => 'Occitan (post 1500)', 'flag' => 'oc'],
        'oj' => ['name' => 'Ojibwa', 'native' => 'Ojibwa', 'flag' => 'oj'],
        'or' => ['name' => 'Oriya', 'native' => 'Oriya', 'flag' => 'or'],
        'om' => ['name' => 'Oromo', 'native' => 'Oromo', 'flag' => 'om'],
        'os' => ['name' => 'Ossetian', 'native' => 'Ossetian', 'flag' => 'os'],
        'pi' => ['name' => 'Pali', 'native' => 'Pali', 'flag' => 'pi'],
        'pa' => ['name' => 'Panjabi', 'native' => 'Panjabi', 'flag' => 'pa'],
        'ps' => ['name' => 'Pashto', 'native' => 'Pashto', 'flag' => 'ps'],
        'fa' => ['name' => 'Persian', 'native' => 'Persian', 'flag' => 'fa'],
        'pl' => ['name' => 'Polish', 'native' => 'Polish', 'flag' => 'pl'],
        'pt' => ['name' => 'Portuguese', 'native' => 'Portuguese', 'flag' => 'pt'],
        'qu' => ['name' => 'Quechua', 'native' => 'Quechua', 'flag' => 'qu'],
        'rm' => ['name' => 'Romansh', 'native' => 'Romansh', 'flag' => 'rm'],
        'rn' => ['name' => 'Rundi', 'native' => 'Rundi', 'flag' => 'rn'],
        'ru' => ['name' => 'Russian', 'native' => 'Russian', 'flag' => 'ru'],
        'sm' => ['name' => 'Samoan', 'native' => 'Samoan', 'flag' => 'sm'],
        'sg' => ['name' => 'Sango', 'native' => 'Sango', 'flag' => 'sg'],
        'sa' => ['name' => 'Sanskrit', 'native' => 'Sanskrit', 'flag' => 'sa'],
        'sc' => ['name' => 'Sardinian', 'native' => 'Sardinian', 'flag' => 'sc'],
        'sr' => ['name' => 'Serbian', 'native' => 'Serbian', 'flag' => 'sr'],
        'sn' => ['name' => 'Shona', 'native' => 'Shona', 'flag' => 'sn'],
        'sd' => ['name' => 'Sindhi', 'native' => 'Sindhi', 'flag' => 'sd'],
        'si' => ['name' => 'Sinhala', 'native' => 'Sinhala', 'flag' => 'si'],
        'sk' => ['name' => 'Slovak', 'native' => 'Slovak', 'flag' => 'sk'],
        'sl' => ['name' => 'Slovenian', 'native' => 'Slovenian', 'flag' => 'si'],
        'so' => ['name' => 'Somali', 'native' => 'Somali', 'flag' => 'so'],
        'st' => ['name' => 'Sotho', 'native' => 'Sotho', 'flag' => 'st'],
        'nr' => ['name' => 'South Ndebele', 'native' => 'South Ndebele', 'flag' => 'nr'],
        'es' => ['name' => 'Spanish', 'native' => 'Spanish', 'flag' => 'es'],
        'su' => ['name' => 'Sundanese', 'native' => 'Sundanese', 'flag' => 'su'],
        'sw' => ['name' => 'Swahili', 'native' => 'Swahili', 'flag' => 'sw'],
        'ss' => ['name' => 'Swati', 'native' => 'Swati', 'flag' => 'ss'],
        'sv' => ['name' => 'Swedish', 'native' => 'Swedish', 'flag' => 'se'],
        'tl' => ['name' => 'Tagalog', 'native' => 'Tagalog', 'flag' => 'tl'],
        'ty' => ['name' => 'Tahitian', 'native' => 'Tahitian', 'flag' => 'ty'],
        'tg' => ['name' => 'Tajik', 'native' => 'Tajik', 'flag' => 'tg'],
        'ta' => ['name' => 'Tamil', 'native' => 'Tamil', 'flag' => 'ta'],
        'tt' => ['name' => 'Tatar', 'native' => 'Tatar', 'flag' => 'tt'],
        'te' => ['name' => 'Telugu', 'native' => 'Telugu', 'flag' => 'te'],
        'th' => ['name' => 'Thai', 'native' => 'Thai', 'flag' => 'th'],
        'bo' => ['name' => 'Tibetan', 'native' => 'Tibetan', 'flag' => 'bo'],
        'ti' => ['name' => 'Tigrinya', 'native' => 'Tigrinya', 'flag' => 'ti'],
        'to' => ['name' => 'Tonga (Tonga Islands)', 'native' => 'Tonga (Tonga Islands)', 'flag' => 'to'],
        'ts' => ['name' => 'Tsonga', 'native' => 'Tsonga', 'flag' => 'ts'],
        'tn' => ['name' => 'Tswana', 'native' => 'Tswana', 'flag' => 'tn'],
        'tr' => ['name' => 'Turkish', 'native' => 'Turkish', 'flag' => 'tr'],
        'tk' => ['name' => 'Turkmen', 'native' => 'Turkmen', 'flag' => 'tk'],
        'tw' => ['name' => 'Twi', 'native' => 'Twi', 'flag' => 'tw'],
        'ug' => ['name' => 'Uighur', 'native' => 'Uighur', 'flag' => 'ug'],
        'uk' => ['name' => 'Ukrainian', 'native' => 'Ukrainian', 'flag' => 'uk'],
        'ur' => ['name' => 'Urdu', 'native' => 'Urdu', 'flag' => 'ur'],
        'uz' => ['name' => 'Uzbek', 'native' => 'Uzbek', 'flag' => 'uz'],
        've' => ['name' => 'Venda', 'native' => 'Venda', 'flag' => 've'],
        'vi' => ['name' => 'Vietnamese', 'native' => 'Vietnamese', 'flag' => 'vi'],
        'vo' => ['name' => 'Volap_k', 'native' => 'Volap_k', 'flag' => 'vo'],
        'wa' => ['name' => 'Walloon', 'native' => 'Walloon', 'flag' => 'wa'],
        'cy' => ['name' => 'Welsh', 'native' => 'Welsh', 'flag' => 'cy'],
        'fy' => ['name' => 'Western Frisian', 'native' => 'Western Frisian', 'flag' => 'fy'],
        'wo' => ['name' => 'Wolof', 'native' => 'Wolof', 'flag' => 'wo'],
        'xh' => ['name' => 'Xhosa', 'native' => 'Xhosa', 'flag' => 'xh'],
        'yi' => ['name' => 'Yiddish', 'native' => 'Yiddish', 'flag' => 'yi'],
        'yo' => ['name' => 'Yoruba', 'native' => 'Yoruba', 'flag' => 'yo'],
        'za' => ['name' => 'Zhuang', 'native' => 'Zhuang', 'flag' => 'za'],
        'zu' => ['name' => 'Zulu', 'native' => 'Zulu', 'flag' => 'zu']
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
     * @param string $url Simple url
     * @param string $language language code
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
     * @throws LanguageException
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
                'name' => $this->getNameByLanguageCode($key),
                'flag' => $this->getFlagByLanguageCode($key),
                'native' => $this->getNativeNameByLanguageCode($key),
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
    public function getNameByLanguageCode(string $code): string
    {
        $name = $this->_languages[$code]['name'] ?? null;

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
    public function getNativeNameByLanguageCode(string $code): string
    {
        $name = $this->_languages[$code]['native'] ?? null;

        if ($name === null) {
            throw new LanguageException("Language name property not found!");
        }
        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     * @param bool $rectangle Rectangle format image
     * @param bool $html Return html <img src="..
     *
     * @return string
     * @throws LanguageException
     */
    public function getFlagByLanguageCode(
        string $code,
        $rectangle = true,
        $html = false
    ): string {

        $flag = $this->_languages[$code]['flag'] ?? null;

        if ($flag === null) {
            throw new LanguageException("Language flag property not found!");
        }

        $name = $this->getNameByLanguageCode($code);

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