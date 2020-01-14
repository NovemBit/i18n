<?php


namespace NovemBit\i18n\system\helpers;


class Languages
{
    public static function getLanguage(
        string $key,
        string $by = 'alpha1',
        ?string $return = 'name'
    ) {
        $languages = self::getLanguages();
        foreach ($languages as $language) {
            if (isset($language[$by])
                && is_string($language[$by])
                && $language[$by] == strtolower($key)
            ) {
                if ($return!=null) {
                    return $language[$return] ?? null;
                } else {
                    return $language;
                }
            }
        }
        return null;
    }
    /**
     * ISO 639-1 languages list
     *
     * @var array
     * */
    private static $_languages = [
        [
            'alpha1' => 'ab',
            'name' => 'Abkhazian',
            'native' => 'Abkhazian',
            'flag' => 'ab'
        ],
        [
            'alpha1' => 'aa',
            'name' => 'Afar',
            'native' => 'Afar',
            'flag' => 'aa'
        ],
        [
            'alpha1' => 'af',
            'name' => 'Afrikaans',
            'native' => 'Afrikaans',
            'flag' => 'af'
        ],
        [
            'alpha1' => 'ak',
            'name' => 'Akan',
            'native' => 'Akan',
            'flag' => 'ak'
        ],
        [
            'alpha1' => 'sq',
            'name' => 'Albanian',
            'native' => 'Albanian',
            'flag' => 'sq'
        ],
        ['alpha1' => 'am', 'name' => 'Amharic', 'native' => 'Amharic', 'flag' => 'am'],
        ['alpha1' => 'ar', 'name' => 'Arabic', 'native' => 'Arabic', 'flag' => 'ae', "dir"=>"rtl"],
        ['alpha1' => 'an', 'name' => 'Aragonese', 'native' => 'Aragonese', 'flag' => 'an'],
        ['alpha1' => 'hy', 'name' => 'Armenian', 'native' => 'Armenian', 'flag' => 'am'],
        ['alpha1' => 'as', 'name' => 'Assamese', 'native' => 'Assamese', 'flag' => 'as'],
        ['alpha1' => 'av', 'name' => 'Avaric', 'native' => 'Avaric', 'flag' => 'av'],
        ['alpha1' => 'ae', 'name' => 'Avestan', 'native' => 'Avestan', 'flag' => 'ae'],
        ['alpha1' => 'ay', 'name' => 'Aymara', 'native' => 'Aymara', 'flag' => 'ay'],
        ['alpha1' => 'az', 'name' => 'Azerbaijani', 'native' => 'Azerbaijani', 'flag' => 'az'],
        ['alpha1' => 'bm', 'name' => 'Bambara', 'native' => 'Bambara', 'flag' => 'bm'],
        ['alpha1' => 'ba', 'name' => 'Bashkir', 'native' => 'Bashkir', 'flag' => 'ba'],
        ['alpha1' => 'eu', 'name' => 'Basque', 'native' => 'Basque', 'flag' => 'eu'],
        ['alpha1' => 'be', 'name' => 'Belarusian', 'native' => 'Belarusian', 'flag' => 'be'],
        ['alpha1' => 'bn', 'name' => 'Bengali', 'native' => 'Bengali', 'flag' => 'bn'],
        ['alpha1' => 'bh', 'name' => 'Bihari languages', 'native' => 'Bihari languages', 'flag' => 'bh'],
        ['alpha1' => 'bi', 'name' => 'Bislama', 'native' => 'Bislama', 'flag' => 'bi'],
        ['alpha1' => 'bs', 'name' => 'Bosnian', 'native' => 'Bosnian', 'flag' => 'bs'],
        ['alpha1' => 'br', 'name' => 'Breton', 'native' => 'Breton', 'flag' => 'br'],
        ['alpha1' => 'bg', 'name' => 'Bulgarian', 'native' => 'Bulgarian', 'flag' => 'bg'],
        ['alpha1' => 'my', 'name' => 'Burmese', 'native' => 'Burmese', 'flag' => 'my'],
        ['alpha1' => 'ca', 'name' => 'Catalan', 'native' => 'Catalan', 'flag' => 'ca'],
        ['alpha1' => 'km', 'name' => 'Central Khmer', 'native' => 'Central Khmer', 'flag' => 'km'],
        ['alpha1' => 'ch', 'name' => 'Chamorro', 'native' => 'Chamorro', 'flag' => 'ch'],
        ['alpha1' => 'ce', 'name' => 'Chechen', 'native' => 'Chechen', 'flag' => 'ce'],
        ['alpha1' => 'ny', 'name' => 'Chichewa', 'native' => 'Chichewa', 'flag' => 'mw'],
        ['alpha1' => 'zh', 'name' => 'Chinese', 'native' => 'Chinese', 'flag' => 'zh'],
        ['alpha1' => 'cu', 'name' => 'Church Slavonic', 'native' => 'Church Slavonic', 'flag' => 'cu'],
        ['alpha1' => 'cv', 'name' => 'Chuvash', 'native' => 'Chuvash', 'flag' => 'cv'],
        ['alpha1' => 'kw', 'name' => 'Cornish', 'native' => 'Cornish', 'flag' => 'kw'],
        ['alpha1' => 'co', 'name' => 'Corsican', 'native' => 'Corsican', 'flag' => 'co'],
        ['alpha1' => 'cr', 'name' => 'Cree', 'native' => 'Cree', 'flag' => 'cr'],
        ['alpha1' => 'hr', 'name' => 'Croatian', 'native' => 'Croatian', 'flag' => 'hr'],
        ['alpha1' => 'cs', 'name' => 'Czech', 'native' => 'Czech', 'flag' => 'cz'],
        ['alpha1' => 'da', 'name' => 'Danish', 'native' => 'Danish', 'flag' => 'dk'],
        ['alpha1' => 'dv', 'name' => 'Divehi', 'native' => 'Divehi', 'flag' => 'dv'],
        ['alpha1' => 'nl', 'name' => 'Dutch', 'native' => 'Dutch', 'flag' => 'nl'],
        ['alpha1' => 'dz', 'name' => 'Dzongkha', 'native' => 'Dzongkha', 'flag' => 'dz'],
        ['alpha1' => 'en', 'name' => 'English', 'native' => 'English', 'flag' => 'gb'],
        ['alpha1' => 'eo', 'name' => 'Esperanto', 'native' => 'Esperanto', 'flag' => 'eo'],
        ['alpha1' => 'et', 'name' => 'Estonian', 'native' => 'Estonian', 'flag' => 'ee'],
        ['alpha1' => 'ee', 'name' => 'Ewe', 'native' => 'Ewe', 'flag' => 'ee'],
        ['alpha1' => 'fo', 'name' => 'Faroese', 'native' => 'Faroese', 'flag' => 'fo'],
        ['alpha1' => 'fj', 'name' => 'Fijian', 'native' => 'Fijian', 'flag' => 'fj'],
        ['alpha1' => 'fi', 'name' => 'Finnish', 'native' => 'Finnish', 'flag' => 'fi'],
        ['alpha1' => 'fr', 'name' => 'French', 'native' => 'French', 'flag' => 'fr'],
        ['alpha1' => 'ff', 'name' => 'Fulah', 'native' => 'Fulah', 'flag' => 'ff'],
        ['alpha1' => 'gd', 'name' => 'Gaelic', 'native' => 'Gaelic', 'flag' => 'gd'],
        ['alpha1' => 'gl', 'name' => 'Galician', 'native' => 'Galician', 'flag' => 'gl'],
        ['alpha1' => 'lg', 'name' => 'Ganda', 'native' => 'Ganda', 'flag' => 'lg'],
        ['alpha1' => 'ka', 'name' => 'Georgian', 'native' => 'Georgian', 'flag' => 'ka'],
        ['alpha1' => 'de', 'name' => 'German', 'native' => 'German', 'flag' => 'de'],
        ['alpha1' => 'ki', 'name' => 'Gikuyu (Kikuyu)', 'native' => 'Gikuyu (Kikuyu)', 'flag' => 'ki'],
        ['alpha1' => 'el', 'name' => 'Greek', 'native' => 'Ελληνικά', 'flag' => 'gr'],
        ['alpha1' => 'kl', 'name' => 'Greenlandic', 'native' => 'Greenlandic', 'flag' => 'kl'],
        ['alpha1' => 'gn', 'name' => 'Guarani', 'native' => 'Guarani', 'flag' => 'gn'],
        ['alpha1' => 'gu', 'name' => 'Gujarati', 'native' => 'Gujarati', 'flag' => 'gu'],
        ['alpha1' => 'ht', 'name' => 'Haitian', 'native' => 'Haitian', 'flag' => 'ht'],
        ['alpha1' => 'ha', 'name' => 'Hausa', 'native' => 'Hausa', 'flag' => 'ha'],
        ['alpha1' => 'he', 'name' => 'Hebrew', 'native' => 'Hebrew', 'flag' => 'he'],
        ['alpha1' => 'hz', 'name' => 'Herero', 'native' => 'Herero', 'flag' => 'hz'],
        ['alpha1' => 'hi', 'name' => 'Hindi', 'native' => 'Hindi', 'flag' => 'hi'],
        ['alpha1' => 'ho', 'name' => 'Hiri Motu', 'native' => 'Hiri Motu', 'flag' => 'ho'],
        ['alpha1' => 'hu', 'name' => 'Hungarian', 'native' => 'Hungarian', 'flag' => 'hu'],
        ['alpha1' => 'is', 'name' => 'Icelandic', 'native' => 'Icelandic', 'flag' => 'is'],
        ['alpha1' => 'io', 'name' => 'Ido', 'native' => 'Ido', 'flag' => 'io'],
        ['alpha1' => 'ig', 'name' => 'Igbo', 'native' => 'Igbo', 'flag' => 'ig'],
        ['alpha1' => 'id', 'name' => 'Indonesian', 'native' => 'Indonesian', 'flag' => 'id'],
        ['alpha1' => 'ia', 'name' => 'Interlingua', 'native' => 'Interlingua', 'flag' => 'ia'],
        ['alpha1' => 'ie', 'name' => 'Interlingue', 'native' => 'Interlingue', 'flag' => 'ie'],
        ['alpha1' => 'iu', 'name' => 'Inuktitut', 'native' => 'Inuktitut', 'flag' => 'iu'],
        ['alpha1' => 'ik', 'name' => 'Inupiaq', 'native' => 'Inupiaq', 'flag' => 'ik'],
        ['alpha1' => 'ga', 'name' => 'Irish', 'native' => 'Irish', 'flag' => 'ga'],
        ['alpha1' => 'it', 'name' => 'Italian', 'native' => 'Italian', 'flag' => 'it'],
        ['alpha1' => 'ja', 'name' => 'Japanese', 'native' => 'Japanese', 'flag' => 'jp'],
        ['alpha1' => 'jv', 'name' => 'Javanese', 'native' => 'Javanese', 'flag' => 'jv'],
        ['alpha1' => 'kn', 'name' => 'Kannada', 'native' => 'Kannada', 'flag' => 'kn'],
        ['alpha1' => 'kr', 'name' => 'Kanuri', 'native' => 'Kanuri', 'flag' => 'kr'],
        ['alpha1' => 'ks', 'name' => 'Kashmiri', 'native' => 'Kashmiri', 'flag' => 'ks'],
        ['alpha1' => 'kk', 'name' => 'Kazakh', 'native' => 'Kazakh', 'flag' => 'kk'],
        ['alpha1' => 'rw', 'name' => 'Kinyarwanda', 'native' => 'Kinyarwanda', 'flag' => 'rw'],
        ['alpha1' => 'kv', 'name' => 'Komi', 'native' => 'Komi', 'flag' => 'kv'],
        ['alpha1' => 'kg', 'name' => 'Kongo', 'native' => 'Kongo', 'flag' => 'kg'],
        ['alpha1' => 'ko', 'name' => 'Korean', 'native' => 'Korean', 'flag' => 'kr'],
        ['alpha1' => 'kj', 'name' => 'Kwanyama', 'native' => 'Kwanyama', 'flag' => 'kj'],
        ['alpha1' => 'ku', 'name' => 'Kurdish', 'native' => 'Kurdish', 'flag' => 'ku'],
        ['alpha1' => 'ky', 'name' => 'Kyrgyz', 'native' => 'Kyrgyz', 'flag' => 'ky'],
        ['alpha1' => 'lo', 'name' => 'Lao', 'native' => 'Lao', 'flag' => 'lo'],
        ['alpha1' => 'la', 'name' => 'Latin', 'native' => 'Latin', 'flag' => 'la'],
        ['alpha1' => 'lv', 'name' => 'Latvian', 'native' => 'Latvian', 'flag' => 'lv'],
        ['alpha1' => 'lb', 'name' => 'Letzeburgesch', 'native' => 'Letzeburgesch', 'flag' => 'lb'],
        ['alpha1' => 'li', 'name' => 'Limburgish', 'native' => 'Limburgish', 'flag' => 'li'],
        ['alpha1' => 'ln', 'name' => 'Lingala', 'native' => 'Lingala', 'flag' => 'ln'],
        ['alpha1' => 'lt', 'name' => 'Lithuanian', 'native' => 'Lithuanian', 'flag' => 'lt'],
        ['alpha1' => 'lu', 'name' => 'Luba-Katanga', 'native' => 'Luba-Katanga', 'flag' => 'lu'],
        ['alpha1' => 'mk', 'name' => 'Macedonian', 'native' => 'Macedonian', 'flag' => 'mk'],
        ['alpha1' => 'mg', 'name' => 'Malagasy', 'native' => 'Malagasy', 'flag' => 'mg'],
        ['alpha1' => 'ms', 'name' => 'Malay', 'native' => 'Malay', 'flag' => 'ms'],
        ['alpha1' => 'ml', 'name' => 'Malayalam', 'native' => 'Malayalam', 'flag' => 'ml'],
        ['alpha1' => 'mt', 'name' => 'Maltese', 'native' => 'Maltese', 'flag' => 'mt'],
        ['alpha1' => 'gv', 'name' => 'Manx', 'native' => 'Manx', 'flag' => 'gv'],
        ['alpha1' => 'mi', 'name' => 'Maori', 'native' => 'Maori', 'flag' => 'mi'],
        ['alpha1' => 'mr', 'name' => 'Marathi', 'native' => 'Marathi', 'flag' => 'mr'],
        ['alpha1' => 'mh', 'name' => 'Marshallese', 'native' => 'Marshallese', 'flag' => 'mh'],
        ['alpha1' => 'ro', 'name' => 'Romanian', 'native' => 'Romanian', 'flag' => 'ro'],
        ['alpha1' => 'mn', 'name' => 'Mongolian', 'native' => 'Mongolian', 'flag' => 'mn'],
        ['alpha1' => 'na', 'name' => 'Nauru', 'native' => 'Nauru', 'flag' => 'na'],
        ['alpha1' => 'nv', 'name' => 'Navajo', 'native' => 'Navajo', 'flag' => 'nv'],
        ['alpha1' => 'nd', 'name' => 'Northern Ndebele', 'native' => 'Northern Ndebele', 'flag' => 'nd'],
        ['alpha1' => 'ng', 'name' => 'Ndonga', 'native' => 'Ndonga', 'flag' => 'ng'],
        ['alpha1' => 'ne', 'name' => 'Nepali', 'native' => 'Nepali', 'flag' => 'ne'],
        ['alpha1' => 'se', 'name' => 'Northern Sami', 'native' => 'Northern Sami', 'flag' => 'se'],
        ['alpha1' => 'no', 'name' => 'Norwegian', 'native' => 'Norwegian', 'flag' => 'no'],
        ['alpha1' => 'nb', 'name' => 'Norwegian Bokmål', 'native' => 'Norwegian Bokmål', 'flag' => 'nb'],
        ['alpha1' => 'nn', 'name' => 'Norwegian Nynorsk', 'native' => 'Norwegian Nynorsk', 'flag' => 'nn'],
        ['alpha1' => 'ii', 'name' => 'Nuosu', 'native' => 'Nuosu', 'flag' => 'ii'],
        ['alpha1' => 'oc', 'name' => 'Occitan (post 1500)', 'native' => 'Occitan (post 1500)', 'flag' => 'oc'],
        ['alpha1' => 'oj', 'name' => 'Ojibwa', 'native' => 'Ojibwa', 'flag' => 'oj'],
        ['alpha1' => 'or', 'name' => 'Oriya', 'native' => 'Oriya', 'flag' => 'or'],
        ['alpha1' => 'om', 'name' => 'Oromo', 'native' => 'Oromo', 'flag' => 'om'],
        ['alpha1' => 'os', 'name' => 'Ossetian', 'native' => 'Ossetian', 'flag' => 'os'],
        ['alpha1' => 'pi', 'name' => 'Pali', 'native' => 'Pali', 'flag' => 'pi'],
        ['alpha1' => 'pa', 'name' => 'Panjabi', 'native' => 'Panjabi', 'flag' => 'pa'],
        ['alpha1' => 'ps', 'name' => 'Pashto', 'native' => 'Pashto', 'flag' => 'ps'],
        ['alpha1' => 'fa', 'name' => 'Persian', 'native' => 'Persian', 'flag' => 'fa'],
        ['alpha1' => 'pl', 'name' => 'Polish', 'native' => 'Polish', 'flag' => 'pl'],
        ['alpha1' => 'pt', 'name' => 'Portuguese', 'native' => 'Portuguese', 'flag' => 'pt'],
        ['alpha1' => 'qu', 'name' => 'Quechua', 'native' => 'Quechua', 'flag' => 'qu'],
        ['alpha1' => 'rm', 'name' => 'Romansh', 'native' => 'Romansh', 'flag' => 'rm'],
        ['alpha1' => 'rn', 'name' => 'Rundi', 'native' => 'Rundi', 'flag' => 'rn'],
        ['alpha1' => 'ru', 'name' => 'Russian', 'native' => 'Russian', 'flag' => 'ru'],
        ['alpha1' => 'sm', 'name' => 'Samoan', 'native' => 'Samoan', 'flag' => 'sm'],
        ['alpha1' => 'sg', 'name' => 'Sango', 'native' => 'Sango', 'flag' => 'sg'],
        ['alpha1' => 'sa', 'name' => 'Sanskrit', 'native' => 'Sanskrit', 'flag' => 'sa'],
        ['alpha1' => 'sc', 'name' => 'Sardinian', 'native' => 'Sardinian', 'flag' => 'sc'],
        ['alpha1' => 'sr', 'name' => 'Serbian', 'native' => 'Serbian', 'flag' => 'sr'],
        ['alpha1' => 'sn', 'name' => 'Shona', 'native' => 'Shona', 'flag' => 'sn'],
        ['alpha1' => 'sd', 'name' => 'Sindhi', 'native' => 'Sindhi', 'flag' => 'sd'],
        ['alpha1' => 'si', 'name' => 'Sinhala', 'native' => 'Sinhala', 'flag' => 'si'],
        ['alpha1' => 'sk', 'name' => 'Slovak', 'native' => 'Slovak', 'flag' => 'sk'],
        ['alpha1' => 'sl', 'name' => 'Slovenian', 'native' => 'Slovenian', 'flag' => 'si'],
        ['alpha1' => 'so', 'name' => 'Somali', 'native' => 'Somali', 'flag' => 'so'],
        ['alpha1' => 'st', 'name' => 'Sotho', 'native' => 'Sotho', 'flag' => 'st'],
        ['alpha1' => 'nr', 'name' => 'South Ndebele', 'native' => 'South Ndebele', 'flag' => 'nr'],
        ['alpha1' => 'es', 'name' => 'Spanish', 'native' => 'Spanish', 'flag' => 'es'],
        ['alpha1' => 'su', 'name' => 'Sundanese', 'native' => 'Sundanese', 'flag' => 'su'],
        ['alpha1' => 'sw', 'name' => 'Swahili', 'native' => 'Swahili', 'flag' => 'sw'],
        ['alpha1' => 'ss', 'name' => 'Swati', 'native' => 'Swati', 'flag' => 'ss'],
        ['alpha1' => 'sv', 'name' => 'Swedish', 'native' => 'Swedish', 'flag' => 'se'],
        ['alpha1' => 'tl', 'name' => 'Tagalog', 'native' => 'Tagalog', 'flag' => 'tl'],
        ['alpha1' => 'ty', 'name' => 'Tahitian', 'native' => 'Tahitian', 'flag' => 'ty'],
        ['alpha1' => 'tg', 'name' => 'Tajik', 'native' => 'Tajik', 'flag' => 'tg'],
        ['alpha1' => 'ta', 'name' => 'Tamil', 'native' => 'Tamil', 'flag' => 'ta'],
        ['alpha1' => 'tt', 'name' => 'Tatar', 'native' => 'Tatar', 'flag' => 'tt'],
        ['alpha1' => 'te', 'name' => 'Telugu', 'native' => 'Telugu', 'flag' => 'te'],
        ['alpha1' => 'th', 'name' => 'Thai', 'native' => 'Thai', 'flag' => 'th'],
        ['alpha1' => 'bo', 'name' => 'Tibetan', 'native' => 'Tibetan', 'flag' => 'bo'],
        ['alpha1' => 'ti', 'name' => 'Tigrinya', 'native' => 'Tigrinya', 'flag' => 'ti'],
        ['alpha1' => 'to', 'name' => 'Tonga (Tonga Islands)', 'native' => 'Tonga (Tonga Islands)', 'flag' => 'to'],
        ['alpha1' => 'ts', 'name' => 'Tsonga', 'native' => 'Tsonga', 'flag' => 'ts'],
        ['alpha1' => 'tn', 'name' => 'Tswana', 'native' => 'Tswana', 'flag' => 'tn'],
        ['alpha1' => 'tr', 'name' => 'Turkish', 'native' => 'Turkish', 'flag' => 'tr'],
        ['alpha1' => 'tk', 'name' => 'Turkmen', 'native' => 'Turkmen', 'flag' => 'tk'],
        ['alpha1' => 'tw', 'name' => 'Twi', 'native' => 'Twi', 'flag' => 'tw'],
        ['alpha1' => 'ug', 'name' => 'Uighur', 'native' => 'Uighur', 'flag' => 'ug'],
        ['alpha1' => 'uk', 'name' => 'Ukrainian', 'native' => 'Ukrainian', 'flag' => 'uk'],
        ['alpha1' => 'ur', 'name' => 'Urdu', 'native' => 'Urdu', 'flag' => 'ur'],
        ['alpha1' => 'uz', 'name' => 'Uzbek', 'native' => 'Uzbek', 'flag' => 'uz'],
        ['alpha1' => 've', 'name' => 'Venda', 'native' => 'Venda', 'flag' => 've'],
        ['alpha1' => 'vi', 'name' => 'Vietnamese', 'native' => 'Vietnamese', 'flag' => 'vi'],
        ['alpha1' => 'vo', 'name' => 'Volap_k', 'native' => 'Volap_k', 'flag' => 'vo'],
        ['alpha1' => 'wa', 'name' => 'Walloon', 'native' => 'Walloon', 'flag' => 'wa'],
        ['alpha1' => 'cy', 'name' => 'Welsh', 'native' => 'Welsh', 'flag' => 'cy'],
        ['alpha1' => 'fy', 'name' => 'Western Frisian', 'native' => 'Western Frisian', 'flag' => 'fy'],
        ['alpha1' => 'wo', 'name' => 'Wolof', 'native' => 'Wolof', 'flag' => 'wo'],
        ['alpha1' => 'xh', 'name' => 'Xhosa', 'native' => 'Xhosa', 'flag' => 'xh'],
        ['alpha1' => 'yi', 'name' => 'Yiddish', 'native' => 'Yiddish', 'flag' => 'yi'],
        ['alpha1' => 'yo', 'name' => 'Yoruba', 'native' => 'Yoruba', 'flag' => 'yo'],
        ['alpha1' => 'za', 'name' => 'Zhuang', 'native' => 'Zhuang', 'flag' => 'za'],
        ['alpha1' => 'zu', 'name' => 'Zulu', 'native' => 'Zulu', 'flag' => 'zu']
    ];

    /**
     * @return array
     */
    public static function getLanguages(): array
    {
        return self::$_languages;
    }
}