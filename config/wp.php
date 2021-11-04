<?php

use NovemBit\i18n\component\translation\method\Dummy;
use NovemBit\i18n\component\translation\type\JsonTranslator;
use NovemBit\i18n\component\translation\type\TextTranslator;
use NovemBit\i18n\component\translation\type\UrlTranslator;
use NovemBit\i18n\component\translation\type\XmlTranslator;
use NovemBit\i18n\component\translation\type\HtmlTranslator;
use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\i18n\component\translation\Translation;

$runtime_dir = __DIR__ . '/../runtime';
return [
    'runtime_dir' => $runtime_dir,
    'localization' =>
        [
            'runtime_dir' => $runtime_dir,
            'languages' =>
                [
                    'runtime_dir' => $runtime_dir,
                    'all' =>
                        [

                            [
                                'alpha1' => 'cs',
                                'name' => 'Czech',
                                'native' => 'Czech',
                                'countries' =>
                                    [
                                        'cz',
                                    ],
                            ],
                            [
                                'alpha1' => 'da',
                                'name' => 'Danish',
                                'native' => 'Danish',
                                'countries' =>
                                    [
                                        'dk',
                                    ],
                            ],
                            [
                                'alpha1' => 'el',
                                'name' => 'Greek',
                                'native' => 'Ελληνικά',
                                'countries' =>
                                    [
                                        'gr',
                                    ],
                            ],
                            [
                                'alpha1' => 'et',
                                'name' => 'Estonian',
                                'native' => 'Estonian',
                                'countries' =>
                                    [
                                        'ee',
                                    ],
                            ],
                            [
                                'alpha1' => 'es',
                                'name' => 'Spanish',
                                'native' => 'Spanish',
                                'countries' =>
                                    [
                                        'es',
                                    ],
                            ],
                            [
                                'alpha1' => 'hr',
                                'name' => 'Croatian',
                                'native' => 'Croatian',
                                'countries' =>
                                    [
                                        'hr',
                                    ],
                            ],
                            [
                                'alpha1' => 'ja',
                                'name' => 'Japanese',
                                'native' => 'Japanese',
                                'countries' =>
                                    [
                                        'jp',
                                    ],
                            ],
                            [
                                'alpha1' => 'ko',
                                'name' => 'Korean',
                                'native' => 'Korean',
                                'countries' =>
                                    [
                                        'kr',
                                    ],
                            ],
                            [
                                'alpha1' => 'nl',
                                'name' => 'Dutch',
                                'native' => 'Dutch',
                                'countries' =>
                                    [
                                        'nl',
                                    ],
                            ],
                            [
                                'alpha1' => 'bg',
                                'name' => 'Bulgarian',
                                'native' => 'Bulgarian',
                                'countries' =>
                                    [
                                        'bg',
                                    ],
                            ],
                            [
                                'alpha1' => 'pl',
                                'name' => 'Polish',
                                'native' => 'Polish',
                                'countries' =>
                                    [
                                        'pl',
                                    ],
                            ],
                            [
                                'alpha1' => 'pt',
                                'name' => 'Portuguese',
                                'native' => 'Portuguese',
                                'countries' =>
                                    [
                                        'pt',
                                    ],
                            ],
                            [
                                'alpha1' => 'ro',
                                'name' => 'Romanian',
                                'native' => 'Romanian',
                                'countries' =>
                                    [
                                        'ro',
                                    ],
                            ],
                            [
                                'alpha1' => 'sl',
                                'name' => 'Slovenian',
                                'native' => 'Slovenian',
                                'countries' =>
                                    [
                                        'si',
                                    ],
                            ],
                            [
                                'alpha1' => 'sv',
                                'name' => 'Swedish',
                                'native' => 'Swedish',
                                'countries' =>
                                    [
                                        'se',
                                    ],
                            ],
                            [
                                'alpha1' => 'fr',
                                'name' => 'French',
                                'native' => 'French',
                                'countries' =>
                                    [
                                        'fr',
                                    ],
                            ],
                            [
                                'alpha1' => 'it',
                                'name' => 'Italian',
                                'native' => 'Italian',
                                'countries' =>
                                    [
                                        'it',
                                    ],
                            ],
                            [
                                'alpha1' => 'de',
                                'name' => 'German',
                                'native' => 'German',
                                'countries' =>
                                    [
                                        'de',
                                    ],
                            ],
                            [
                                'alpha1' => 'ru',
                                'name' => 'Russian',
                                'native' => 'Russian',
                                'countries' =>
                                    [
                                        'ru',
                                    ],
                            ],
                            [
                                'alpha1' => 'en',
                                'name' => 'English',
                                'native' => 'English',
                                'countries' =>
                                    [
                                        'gb',
                                    ],
                            ],
                            [
                                'alpha1' => 'gd',
                                'name' => 'Gaelic',
                                'native' => 'Scots Gaelic',
                                'countries' =>
                                    [
                                        'gb',
                                    ],
                            ],
                            [
                                'alpha1' => 'cy',
                                'name' => 'Welsh',
                                'native' => 'Welsh',
                                'countries' =>
                                    [
                                        'gb',
                                    ],
                            ],
                        ],
                ],
            'regions' =>
                [
                    'all' =>
                        [
                            [
                                'name' => 'Africa',
                                'code' => 'af',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                            [
                                'name' => 'North America',
                                'code' => 'na',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                            [
                                'name' => 'Oceania',
                                'code' => 'oc',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                            [
                                'name' => 'Antarctica',
                                'code' => 'an',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                            [
                                'name' => 'Asia',
                                'code' => 'as',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                            [
                                'name' => 'Europe',
                                'code' => 'eu',
                                'domain' => 'swanson.eu.com',
                                'include_languages' => '1',
                            ],
                            [
                                'name' => 'South America',
                                'code' => 'sa',
                                'domain' => '',
                                'include_languages' => '',
                            ],
                        ],
                ],
            'countries' =>
                [
                    'all' =>
                        [
                            [
                                'name' => 'Japan',
                                'alpha2' => 'jp',
                                'alpha3' => 'jpn',
                                'numeric' => '392',
                                'regions' =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'ja',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Bulgaria',
                                'alpha2' => 'bg',
                                'alpha3' => 'bgr',
                                'numeric' => '100',
                                'domain' => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'bg',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Croatia',
                                'alpha2' => 'hr',
                                'alpha3' => 'hrv',
                                'numeric' => '191',
                                'domain' => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'hr',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Czechia',
                                'alpha2' => 'cz',
                                'alpha3' => 'cze',
                                'numeric' => '203',
                                'domain' => 'swanson.co.cz',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'cs',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Denmark',
                                'alpha2' => 'dk',
                                'alpha3' => 'dnk',
                                'numeric' => '208',
                                'domain' => 'swanson.co.dk',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'da',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Estonia',
                                'alpha2' => 'ee',
                                'alpha3' => 'est',
                                'numeric' => '233',
                                'domain' => 'swanson.ee',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'et',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'France',
                                'alpha2' => 'fr',
                                'alpha3' => 'fra',
                                'numeric' => '250',
                                'domain' => 'swanson.fr',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'fr',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Germany',
                                'alpha2' => 'de',
                                'alpha3' => 'deu',
                                'numeric' => '276',
                                'domain' => 'swanson.co.de',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'de',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Italy',
                                'alpha2' => 'it',
                                'alpha3' => 'ita',
                                'numeric' => '380',
                                'domain' => 'swanson.it',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'it',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Netherlands',
                                'alpha2' => 'nl',
                                'alpha3' => 'nld',
                                'numeric' => '528',
                                'domain' => 'swanson.nl',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'nl',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Poland',
                                'alpha2' => 'pl',
                                'alpha3' => 'pol',
                                'numeric' => '616',
                                'domain' => 'swanson.pl',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'pl',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Portugal',
                                'alpha2' => 'pt',
                                'alpha3' => 'prt',
                                'numeric' => '620',
                                'domain' => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'pt',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Romania',
                                'alpha2' => 'ro',
                                'alpha3' => 'rou',
                                'numeric' => '642',
                                'domain' => 'swanson.co.ro',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'ro',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Slovenia',
                                'alpha2' => 'si',
                                'alpha3' => 'svn',
                                'numeric' => '705',
                                'domain' => 'swanson.si',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'sl',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Spain',
                                'alpha2' => 'es',
                                'alpha3' => 'esp',
                                'numeric' => '724',
                                'domain' => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'es',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'United Kingdom of Great Britain',
                                'alpha2' => 'gb',
                                'alpha3' => 'gbr',
                                'numeric' => '826',
                                'domain' => 'swanson.co.uk',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                        'gd',
                                        'cv',
                                    ],
                            ],
                            [
                                'name' => 'Korea (Republic of)',
                                'alpha2' => 'kr',
                                'alpha3' => 'kor',
                                'numeric' => '410',
                                'domain' => 'swanson.kr',
                                'regions' =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'ko',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Singapore',
                                'alpha2' => 'sg',
                                'alpha3' => 'sgp',
                                'numeric' => '702',
                                'domain' => 'swanson.sg',
                                'regions' =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'New Zealand',
                                'alpha2' => 'nz',
                                'alpha3' => 'nzl',
                                'numeric' => '554',
                                'domain' => 'swanson.co.nz',
                                'regions' =>
                                    [
                                        'oc',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Greece',
                                'alpha2' => 'gr',
                                'alpha3' => 'grc',
                                'numeric' => '300',
                                'domain' => 'swanson.gr',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'el',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Sweden',
                                'alpha2' => 'se',
                                'alpha3' => 'swe',
                                'numeric' => '752',
                                'domain' => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'sv',
                                        'en',
                                    ],
                            ],
                            [
                                'name' => 'Russian',
                                'alpha2' => 'ru',
                                'alpha3' => 'rus',
                                'numeric' => '643',
                                'domain' => 'swanson.ru',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'ru',
                                        'en',
                                    ],
                            ],
                        ],
                ],
            'from_language' => 'en',
            'accept_languages' =>
                [
                    'cs',
                    'da',
                    'el',
                    'et',
                    'es',
                    'hr',
                    'ja',
                    'ko',
                    'nl',
                    'bg',
                    'pl',
                    'pt',
                    'ro',
                    'sl',
                    'sv',
                    'fr',
                    'it',
                    'de',
                    'ru',
                    'en',
                    'cy',
                    'gd',
                ],
            'localize_host' => true,
            'path_exclusion_patterns' =>
                [
                    '.*\\.php',
                    '.*wp-admin',
                    '.*wp-json',
                    '(?<=^search)\\/.*$',
                    '^aff\\/.*$',
                ],
            'global_domains' =>
                [
                    'swanson.co.uk',
                ],
            'localization_config' =>
                [],
        ],
    'translation' =>
        [
            'class' => Translation::class,
            'runtime_dir' => $runtime_dir,
            'method' =>
                [
                    'class' => Dummy::class,
                    'runtime_dir' => $runtime_dir,
                    'save_translations' => true,
                    'exclusions' =>
                        [
                            'vitamin',
                            'Adidas',
                            'Terry Naturally',
                            'Twinlab',
                            'Shearer Candles',
                            'Stella Sport',
                            'Planetary Herbals',
                            'Reebok',
                            'Fairhaven Health',
                            'Garden of Life',
                            'Dr. Mercola',
                            'Ellyndale',
                            'Doctor\'s Best',
                            'Cosmesis Skin Care (by Life Extension)',
                            'Bounce',
                            'Now Foods',
                            'Jarrow Formulas',
                            'Pip & Nut',
                            'Liberation',
                            'PraNaturals',
                            'Life Extension',
                            'Regime London',
                            'Metabolife',
                            'Source Naturals',
                            'Milkies',
                            'Swanson',
                            'Natural Factors',
                            'Trèsutopia',
                            'Natures Aid',
                            'Brandlight',
                            'Activpet',
                        ],
                ],
            'text' =>
                [
                    'class' => TextTranslator::class,
                    'runtime_dir' => $runtime_dir,
                    'save_translations' => true,
                ],
            'url' =>
                [
                    'class' => UrlTranslator::class,
                    'runtime_dir' => $runtime_dir,
                    'path_separator' => '-',
                    'path_translation' => true,
                    'path_lowercase' => true,
                    'url_validation_rules' =>
                        [
                            'scheme' =>
                                [
                                    '^(https?)?$',
                                ],
                            'host' =>
                                [
                                    '^$|^swanson\\.co\\.uk$|^swanson\\.co\\.uk$',
                                ],
                            'path' =>
                                [
                                    '^.*(?<!\\.js|\\.css|\\.map|\\.png|\\.gif|\\.webp|\\.jpg|\\.sass|\\.less)$',
                                ],
                        ],
                    'path_exclusion_patterns' =>
                        [
                            '/\\/var\\/.*/is',
                            '/sitemap\\.xml/is',
                            '/sitemap-index\\.xml/is',
                        ],
                ],
            'xml' =>
                [
                    'class' => XmlTranslator::class,
                    'runtime_dir' => $runtime_dir,
                ],
            'sitemap_xml' =>
                [
                    'class' => XmlTranslator::class,
                    'name' => 'sitemap_xml',
                    'runtime_dir' => $runtime_dir,
                    'xpath_query_map' =>
                        [
                            'accept' =>
                                [
                                    '/*/*/*[1]/text()' =>
                                        [
                                            'type' => 'url',
                                        ],
                                ],
                        ],
                ],
            'gpf_xml' =>
                [
                    'class' => XmlTranslator::class,
                    'name' => 'gpf_xml',
                    'runtime_dir' => $runtime_dir,
                    'xpath_query_map' =>
                        [
                            'accept' =>
                                [
                                    '//*[name()=\'title\']/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[name()=\'description\']/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[name()=\'link\']/text()' =>
                                        [
                                            'type' => 'url',
                                        ],
                                ],
                        ],
                ],
            'html' =>
                [
                    'class' => HtmlTranslator::class,
                    'runtime_dir' => $runtime_dir,
                    'title_tag_template' => static function (array $params) {
                        $a = mb_convert_case(
                            $params['country_native'] ?? ($params['region_native'] ?? ''),
                            MB_CASE_TITLE,
                            "UTF-8"
                        );
                        $b = mb_convert_case(
                            ($params['language_native'] ?? $params['language_name'] ?? ''),
                            MB_CASE_TITLE,
                            "UTF-8"
                        );

                        return sprintf(
                            '%s | %s %s',
                            $params['translate'],
                            !empty($a) ? $a . ',' : '',
                            $b
                        );
                    },
                    'xpath_query_map' =>
                        [
                            'ignore' =>
                                [
                                    'ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main" or contains(@class,"dont-translate")]',
                                ],
                            'accept' =>
                                [
                                    '//head/title/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/meta[@name="description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/link[@rel="canonical" or @rel="next"][1]/@href' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//head/meta[@property="og:title" or @property="og:description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/meta[@property="og:url"]/@content' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//head/meta[@name="twitter:title" or @name="twitter:description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//script[@type="application/ld+json"]/text()' =>
                                        [
                                            'type' => 'jsonld',
                                        ],
                                    '//*[(self::a or self::strong) and starts-with(text(), "http://default.wp")]/text()' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//input[(@id="affwp-url") and contains(@value, "http://default.wp")]/@value' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//p/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//time/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//small/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//strong/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//b/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//bold/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//italic/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//i/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//td/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//th/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//li/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//lo/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h1/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h2/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h3/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h4/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h5/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h6/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//dt/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//dd/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//a/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//span/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//div/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//label/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@title' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@alt' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@data-tooltip' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@data-tip' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::textarea or self::input]/@placeholder' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::input[@type="button" or @type="submit"]]/@value' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::button]/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//a/@href' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//form/@action' =>
                                        [
                                            'type' => 'url',
                                        ],
                                ],
                        ],
                    'save_translations' => false,
                ],
            'html_fragment' =>
                [
                    'class' => 'NovemBit\\i18n\\component\\translation\\type\\HtmlFragmentTranslator',
                    'runtime_dir' => $runtime_dir,
                    'xpath_query_map' =>
                        [
                            'ignore' =>
                                [
                                    'ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main" or contains(@class,"dont-translate")]',
                                ],
                            'accept' =>
                                [
                                    '//head/title/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/meta[@name="description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/link[@rel="canonical" or @rel="next"][1]/@href' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//head/meta[@property="og:title" or @property="og:description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//head/meta[@property="og:url"]/@content' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//head/meta[@name="twitter:title" or @name="twitter:description"]/@content' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//script[@type="application/ld+json"]/text()' =>
                                        [
                                            'type' => 'jsonld',
                                        ],
                                    '//*[(self::a or self::strong) and starts-with(text(), "http://default.wp")]/text()' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//input[(@id="affwp-url") and contains(@value, "http://default.wp")]/@value' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//p/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//time/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//small/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//strong/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//b/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//bold/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//italic/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//i/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//td/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//th/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//li/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//lo/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h1/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h2/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h3/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h4/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h5/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//h6/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//dt/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//dd/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//a/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//span/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//div/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//label/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@title' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@alt' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@data-tooltip' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//@data-tip' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::textarea or self::input]/@placeholder' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::input[@type="button" or @type="submit"]]/@value' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//*[self::button]/text()' =>
                                        [
                                            'type' => 'text',
                                        ],
                                    '//a/@href' =>
                                        [
                                            'type' => 'url',
                                        ],
                                    '//form/@action' =>
                                        [
                                            'type' => 'url',
                                        ],
                                ],
                        ],
                    'cache_result' => true,
                ],
            'json' =>
                [
                    'class' => JsonTranslator::class,
                    'runtime_dir' => $runtime_dir,
                    'save_translations' => false,
                    'fields_to_translate' =>
                        [
                            '/^price_html$/i' => 'html',
                            '/^availability_html$/i' => 'html',
                        ],
                ],
            'jsonld' =>
                [
                    'class' => JsonTranslator::class,
                    'runtime_dir' => $runtime_dir,
                    'name' => 'jsonld',
                    'save_translations' => false,
                    'type_autodetect' => false,
                    'fields_to_translate' =>
                        [
                            '/^(name|description)$/i' => 'text',
                            '/^(@id|url)/i' => 'url',
                            '/^(?>@?\\w+>)+(name|description$|reviewBody)$/i' => 'text',
                            '/^(?>@?\\w+>)+(url|@id|sameAs)$/i' => 'url',
                            '/^potentialAction>target$/' => static function ($val, $language) {
                                $main_domain = parse_url($val, PHP_URL_HOST);
                                $current_domain = $_SERVER['HTTP_HOST'] ?? null;

                                if ($main_domain != $current_domain) {
                                    $val = str_replace($main_domain, $current_domain, $val);
                                }
                                return $val;
                            },
                            '/^(?>@?\\w+>)+category$/i' => 'html_fragment',
                        ],
                ],
        ],
    'request' =>
        [
            'runtime_dir' => $runtime_dir,
            'restore_non_translated_urls' => true,
            'allow_editor' => true,
            'default_http_host' => 'swanson.co.uk',
            'localization_redirects' => true,
            'source_type_map' =>
                [
                    '/woocommerce_gpf\\/google.*/is' => 'gpf_xml',
                    '/sitemap.xml/is' => 'sitemap_xml',
                    '/sitemap-index.xml/is' => 'sitemap_xml',
                ],
            'exclusions' =>
                [
                ],
            'on_page_not_found' => null,
            'editor_after_save_callback' => null
        ],
    'rest' =>
        [
            'runtime_dir' => $runtime_dir,
            'api_keys' =>
                [
                    'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv',
                ],
        ],
    'db' =>
        [
            'runtime_dir' => $runtime_dir,
            'connection_params' =>
                [
                    'path' => __DIR__ . '/../runtime/db/db.db',
                    'driver' => 'pdo_sqlite',
                ],
        ],
];
