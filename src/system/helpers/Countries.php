<?php


namespace NovemBit\i18n\system\helpers;


class Countries extends LocalData
{

    /**
     * @param string      $key
     * @param string      $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public static function getCountry(
        string $key,
        string $by = 'alpha2',
        ?string $return = 'name'
    ) {
        return self::get($key, $by, $return);
    }

    /**
     * @return array[]
     */
    public static function getData(): array
    {
        return [

            [
                'name' => 'Afghanistan',
                'alpha2' => 'af',
                'alpha3' => 'afg',
                'numeric' => '004',
                'currency' =>
                    [
                        'AFN',
                    ],
            ],

            [
                'name' => 'Åland Islands',
                'alpha2' => 'ax',
                'alpha3' => 'ala',
                'numeric' => '248',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Albania',
                'alpha2' => 'al',
                'alpha3' => 'alb',
                'numeric' => '008',
                'currency' =>
                    [
                        'ALL',
                    ],
            ],

            [
                'name' => 'Algeria',
                'alpha2' => 'dz',
                'alpha3' => 'dza',
                'numeric' => '012',
                'currency' =>
                    [
                        'DZD',
                    ],
            ],

            [
                'name' => 'American Samoa',
                'alpha2' => 'as',
                'alpha3' => 'asm',
                'numeric' => '016',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Andorra',
                'alpha2' => 'ad',
                'alpha3' => 'and',
                'numeric' => '020',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Angola',
                'alpha2' => 'ao',
                'alpha3' => 'ago',
                'numeric' => '024',
                'currency' =>
                    [
                        'AOA',
                    ],
            ],

            [
                'name' => 'Anguilla',
                'alpha2' => 'ai',
                'alpha3' => 'aia',
                'numeric' => '660',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Antarctica',
                'alpha2' => 'aq',
                'alpha3' => 'ata',
                'numeric' => '010',
                'currency' =>
                    [
                        'ARS',
                        'AUD',
                        'BGN',
                        'BRL',
                        'BYR',
                        'CLP',
                        'CNY',
                        'CZK',
                        'EUR',
                        'GBP',
                        'INR',
                        'JPY',
                        'KRW',
                        'NOK',
                        'NZD',
                        'PEN',
                        'PKR',
                        'PLN',
                        'RON',
                        'RUB',
                        'SEK',
                        'UAH',
                        'USD',
                        'UYU',
                        'ZAR',
                    ],
            ],

            [
                'name' => 'Antigua and Barbuda',
                'alpha2' => 'ag',
                'alpha3' => 'atg',
                'numeric' => '028',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Argentina',
                'alpha2' => 'ar',
                'alpha3' => 'arg',
                'numeric' => '032',
                'currency' =>
                    [
                        'ARS',
                    ],
            ],

            [
                'name' => 'Armenia',
                'alpha2' => 'am',
                'alpha3' => 'arm',
                'numeric' => '051',
                'currency' =>
                    [
                        'AMD',
                    ],
            ],

            [
                'name' => 'Aruba',
                'alpha2' => 'aw',
                'alpha3' => 'abw',
                'numeric' => '533',
                'currency' =>
                    [
                        'AWG',
                    ],
            ],

            [
                'name' => 'Australia',
                'alpha2' => 'au',
                'alpha3' => 'aus',
                'numeric' => '036',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Austria',
                'alpha2' => 'at',
                'alpha3' => 'aut',
                'numeric' => '040',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Azerbaijan',
                'alpha2' => 'az',
                'alpha3' => 'aze',
                'numeric' => '031',
                'currency' =>
                    [
                        'AZN',
                    ],
            ],

            [
                'name' => 'Bahamas',
                'alpha2' => 'bs',
                'alpha3' => 'bhs',
                'numeric' => '044',
                'currency' =>
                    [
                        'BSD',
                    ],
            ],

            [
                'name' => 'Bahrain',
                'alpha2' => 'bh',
                'alpha3' => 'bhr',
                'numeric' => '048',
                'currency' =>
                    [
                        'BHD',
                    ],
            ],

            [
                'name' => 'Bangladesh',
                'alpha2' => 'bd',
                'alpha3' => 'bgd',
                'numeric' => '050',
                'currency' =>
                    [
                        'BDT',
                    ],
            ],

            [
                'name' => 'Barbados',
                'alpha2' => 'bb',
                'alpha3' => 'brb',
                'numeric' => '052',
                'currency' =>
                    [
                        'BBD',
                    ],
            ],

            [
                'name' => 'Belarus',
                'alpha2' => 'by',
                'alpha3' => 'blr',
                'numeric' => '112',
                'currency' =>
                    [
                        'BYN',
                    ],
            ],

            [
                'name' => 'Belgium',
                'alpha2' => 'be',
                'alpha3' => 'bel',
                'numeric' => '056',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Belize',
                'alpha2' => 'bz',
                'alpha3' => 'blz',
                'numeric' => '084',
                'currency' =>
                    [
                        'BZD',
                    ],
            ],

            [
                'name' => 'Benin',
                'alpha2' => 'bj',
                'alpha3' => 'ben',
                'numeric' => '204',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Bermuda',
                'alpha2' => 'bm',
                'alpha3' => 'bmu',
                'numeric' => '060',
                'currency' =>
                    [
                        'BMD',
                    ],
            ],

            [
                'name' => 'Bhutan',
                'alpha2' => 'bt',
                'alpha3' => 'btn',
                'numeric' => '064',
                'currency' =>
                    [
                        'BTN',
                    ],
            ],

            [
                'name' => 'Bolivia (Plurinational State of)',
                'alpha2' => 'bo',
                'alpha3' => 'bol',
                'numeric' => '068',
                'currency' =>
                    [
                        'BOB',
                    ],
            ],

            [
                'name' => 'Bonaire, Sint Eustatius and Saba',
                'alpha2' => 'bq',
                'alpha3' => 'bes',
                'numeric' => '535',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Bosnia and Herzegovina',
                'alpha2' => 'ba',
                'alpha3' => 'bih',
                'numeric' => '070',
                'currency' =>
                    [
                        'BAM',
                    ],
            ],

            [
                'name' => 'Botswana',
                'alpha2' => 'bw',
                'alpha3' => 'bwa',
                'numeric' => '072',
                'currency' =>
                    [
                        'BWP',
                    ],
            ],

            [
                'name' => 'Bouvet Island',
                'alpha2' => 'bv',
                'alpha3' => 'bvt',
                'numeric' => '074',
                'currency' =>
                    [
                        'NOK',
                    ],
            ],

            [
                'name' => 'Brazil',
                'alpha2' => 'br',
                'alpha3' => 'bra',
                'numeric' => '076',
                'currency' =>
                    [
                        'BRL',
                    ],
            ],

            [
                'name' => 'British Indian Ocean Territory',
                'alpha2' => 'io',
                'alpha3' => 'iot',
                'numeric' => '086',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'Brunei Darussalam',
                'alpha2' => 'bn',
                'alpha3' => 'brn',
                'numeric' => '096',
                'currency' =>
                    [
                        'BND',
                        'SGD',
                    ],
            ],

            [
                'name' => 'Bulgaria',
                'alpha2' => 'bg',
                'alpha3' => 'bgr',
                'numeric' => '100',
                'currency' =>
                    [
                        'BGN',
                    ],
            ],

            [
                'name' => 'Burkina Faso',
                'alpha2' => 'bf',
                'alpha3' => 'bfa',
                'numeric' => '854',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Burundi',
                'alpha2' => 'bi',
                'alpha3' => 'bdi',
                'numeric' => '108',
                'currency' =>
                    [
                        'BIF',
                    ],
            ],

            [
                'name' => 'Cabo Verde',
                'alpha2' => 'cv',
                'alpha3' => 'cpv',
                'numeric' => '132',
                'currency' =>
                    [
                        'CVE',
                    ],
            ],

            [
                'name' => 'Cambodia',
                'alpha2' => 'kh',
                'alpha3' => 'khm',
                'numeric' => '116',
                'currency' =>
                    [
                        'KHR',
                    ],
            ],

            [
                'name' => 'Cameroon',
                'alpha2' => 'cm',
                'alpha3' => 'cmr',
                'numeric' => '120',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Canada',
                'alpha2' => 'ca',
                'alpha3' => 'can',
                'numeric' => '124',
                'currency' =>
                    [
                        'CAD',
                    ],
            ],

            [
                'name' => 'Cayman Islands',
                'alpha2' => 'ky',
                'alpha3' => 'cym',
                'numeric' => '136',
                'currency' =>
                    [
                        'KYD',
                    ],
            ],

            [
                'name' => 'Central African Republic',
                'alpha2' => 'cf',
                'alpha3' => 'caf',
                'numeric' => '140',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Chad',
                'alpha2' => 'td',
                'alpha3' => 'tcd',
                'numeric' => '148',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Chile',
                'alpha2' => 'cl',
                'alpha3' => 'chl',
                'numeric' => '152',
                'currency' =>
                    [
                        'CLP',
                    ],
            ],

            [
                'name' => 'China',
                'alpha2' => 'cn',
                'alpha3' => 'chn',
                'numeric' => '156',
                'currency' =>
                    [
                        'CNY',
                    ],
            ],

            [
                'name' => 'Christmas Island',
                'alpha2' => 'cx',
                'alpha3' => 'cxr',
                'numeric' => '162',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Cocos (Keeling) Islands',
                'alpha2' => 'cc',
                'alpha3' => 'cck',
                'numeric' => '166',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Colombia',
                'alpha2' => 'co',
                'alpha3' => 'col',
                'numeric' => '170',
                'currency' =>
                    [
                        'COP',
                    ],
            ],

            [
                'name' => 'Comoros',
                'alpha2' => 'km',
                'alpha3' => 'com',
                'numeric' => '174',
                'currency' =>
                    [
                        'KMF',
                    ],
            ],

            [
                'name' => 'Congo',
                'alpha2' => 'cg',
                'alpha3' => 'cog',
                'numeric' => '178',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Congo (Democratic Republic of the)',
                'alpha2' => 'cd',
                'alpha3' => 'cod',
                'numeric' => '180',
                'currency' =>
                    [
                        'CDF',
                    ],
            ],

            [
                'name' => 'Cook Islands',
                'alpha2' => 'ck',
                'alpha3' => 'cok',
                'numeric' => '184',
                'currency' =>
                    [
                        'NZD',
                    ],
            ],

            [
                'name' => 'Costa Rica',
                'alpha2' => 'cr',
                'alpha3' => 'cri',
                'numeric' => '188',
                'currency' =>
                    [
                        'CRC',
                    ],
            ],

            [
                'name' => 'Côte d\'Ivoire',
                'alpha2' => 'ci',
                'alpha3' => 'civ',
                'numeric' => '384',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Croatia',
                'alpha2' => 'hr',
                'alpha3' => 'hrv',
                'numeric' => '191',
                'currency' =>
                    [
                        'HRK',
                    ],
            ],

            [
                'name' => 'Cuba',
                'alpha2' => 'cu',
                'alpha3' => 'cub',
                'numeric' => '192',
                'currency' =>
                    [
                        'CUC',
                        'CUP',
                    ],
            ],

            [
                'name' => 'Curaçao',
                'alpha2' => 'cw',
                'alpha3' => 'cuw',
                'numeric' => '531',
                'currency' =>
                    [
                        'ANG',
                    ],
            ],

            [
                'name' => 'Cyprus',
                'alpha2' => 'cy',
                'alpha3' => 'cyp',
                'numeric' => '196',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Czechia',
                'alpha2' => 'cz',
                'alpha3' => 'cze',
                'numeric' => '203',
                'currency' =>
                    [
                        'CZK',
                    ],
            ],

            [
                'name' => 'Denmark',
                'alpha2' => 'dk',
                'alpha3' => 'dnk',
                'numeric' => '208',
                'currency' =>
                    [
                        'DKK',
                    ],
            ],

            [
                'name' => 'Djibouti',
                'alpha2' => 'dj',
                'alpha3' => 'dji',
                'numeric' => '262',
                'currency' =>
                    [
                        'DJF',
                    ],
            ],

            [
                'name' => 'Dominica',
                'alpha2' => 'dm',
                'alpha3' => 'dma',
                'numeric' => '212',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Dominican Republic',
                'alpha2' => 'do',
                'alpha3' => 'dom',
                'numeric' => '214',
                'currency' =>
                    [
                        'DOP',
                    ],
            ],

            [
                'name' => 'Ecuador',
                'alpha2' => 'ec',
                'alpha3' => 'ecu',
                'numeric' => '218',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Egypt',
                'alpha2' => 'eg',
                'alpha3' => 'egy',
                'numeric' => '818',
                'currency' =>
                    [
                        'EGP',
                    ],
            ],

            [
                'name' => 'El Salvador',
                'alpha2' => 'sv',
                'alpha3' => 'slv',
                'numeric' => '222',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Equatorial Guinea',
                'alpha2' => 'gq',
                'alpha3' => 'gnq',
                'numeric' => '226',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Eritrea',
                'alpha2' => 'er',
                'alpha3' => 'eri',
                'numeric' => '232',
                'currency' =>
                    [
                        'ERN',
                    ],
            ],

            [
                'name' => 'Estonia',
                'alpha2' => 'ee',
                'alpha3' => 'est',
                'numeric' => '233',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Ethiopia',
                'alpha2' => 'et',
                'alpha3' => 'eth',
                'numeric' => '231',
                'currency' =>
                    [
                        'ETB',
                    ],
            ],

            [
                'name' => 'Eswatini',
                'alpha2' => 'sz',
                'alpha3' => 'swz',
                'numeric' => '748',
                'currency' =>
                    [
                        'SZL',
                        'ZAR',
                    ],
            ],

            [
                'name' => 'Falkland Islands (Malvinas)',
                'alpha2' => 'fk',
                'alpha3' => 'flk',
                'numeric' => '238',
                'currency' =>
                    [
                        'FKP',
                    ],
            ],

            [
                'name' => 'Faroe Islands',
                'alpha2' => 'fo',
                'alpha3' => 'fro',
                'numeric' => '234',
                'currency' =>
                    [
                        'DKK',
                    ],
            ],

            [
                'name' => 'Fiji',
                'alpha2' => 'fj',
                'alpha3' => 'fji',
                'numeric' => '242',
                'currency' =>
                    [
                        'FJD',
                    ],
            ],

            [
                'name' => 'Finland',
                'alpha2' => 'fi',
                'alpha3' => 'fin',
                'numeric' => '246',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'France',
                'alpha2' => 'fr',
                'alpha3' => 'fra',
                'numeric' => '250',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'French Guiana',
                'alpha2' => 'gf',
                'alpha3' => 'guf',
                'numeric' => '254',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'French Polynesia',
                'alpha2' => 'pf',
                'alpha3' => 'pyf',
                'numeric' => '258',
                'currency' =>
                    [
                        'XPF',
                    ],
            ],

            [
                'name' => 'French Southern Territories',
                'alpha2' => 'tf',
                'alpha3' => 'atf',
                'numeric' => '260',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Gabon',
                'alpha2' => 'ga',
                'alpha3' => 'gab',
                'numeric' => '266',
                'currency' =>
                    [
                        'XAF',
                    ],
            ],

            [
                'name' => 'Gambia',
                'alpha2' => 'gm',
                'alpha3' => 'gmb',
                'numeric' => '270',
                'currency' =>
                    [
                        'GMD',
                    ],
            ],

            [
                'name' => 'Georgia',
                'alpha2' => 'ge',
                'alpha3' => 'geo',
                'numeric' => '268',
                'currency' =>
                    [
                        'GEL',
                    ],
            ],

            [
                'name' => 'Germany',
                'alpha2' => 'de',
                'alpha3' => 'deu',
                'numeric' => '276',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Ghana',
                'alpha2' => 'gh',
                'alpha3' => 'gha',
                'numeric' => '288',
                'currency' =>
                    [
                        'GHS',
                    ],
            ],

            [
                'name' => 'Gibraltar',
                'alpha2' => 'gi',
                'alpha3' => 'gib',
                'numeric' => '292',
                'currency' =>
                    [
                        'GIP',
                    ],
            ],

            [
                'name' => 'Greece',
                'alpha2' => 'gr',
                'alpha3' => 'grc',
                'numeric' => '300',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Greenland',
                'alpha2' => 'gl',
                'alpha3' => 'grl',
                'numeric' => '304',
                'currency' =>
                    [
                        'DKK',
                    ],
            ],

            [
                'name' => 'Grenada',
                'alpha2' => 'gd',
                'alpha3' => 'grd',
                'numeric' => '308',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Guadeloupe',
                'alpha2' => 'gp',
                'alpha3' => 'glp',
                'numeric' => '312',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Guam',
                'alpha2' => 'gu',
                'alpha3' => 'gum',
                'numeric' => '316',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Guatemala',
                'alpha2' => 'gt',
                'alpha3' => 'gtm',
                'numeric' => '320',
                'currency' =>
                    [
                        'GTQ',
                    ],
            ],

            [
                'name' => 'Guernsey',
                'alpha2' => 'gg',
                'alpha3' => 'ggy',
                'numeric' => '831',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'Guinea',
                'alpha2' => 'gn',
                'alpha3' => 'gin',
                'numeric' => '324',
                'currency' =>
                    [
                        'GNF',
                    ],
            ],

            [
                'name' => 'Guinea-Bissau',
                'alpha2' => 'gw',
                'alpha3' => 'gnb',
                'numeric' => '624',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Guyana',
                'alpha2' => 'gy',
                'alpha3' => 'guy',
                'numeric' => '328',
                'currency' =>
                    [
                        'GYD',
                    ],
            ],

            [
                'name' => 'Haiti',
                'alpha2' => 'ht',
                'alpha3' => 'hti',
                'numeric' => '332',
                'currency' =>
                    [
                        'HTG',
                    ],
            ],

            [
                'name' => 'Heard Island and McDonald Islands',
                'alpha2' => 'hm',
                'alpha3' => 'hmd',
                'numeric' => '334',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Holy See',
                'alpha2' => 'va',
                'alpha3' => 'vat',
                'numeric' => '336',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Honduras',
                'alpha2' => 'hn',
                'alpha3' => 'hnd',
                'numeric' => '340',
                'currency' =>
                    [
                        'HNL',
                    ],
            ],

            [
                'name' => 'Hong Kong',
                'alpha2' => 'hk',
                'alpha3' => 'hkg',
                'numeric' => '344',
                'currency' =>
                    [
                        'HKD',
                    ],
            ],

            [
                'name' => 'Hungary',
                'alpha2' => 'hu',
                'alpha3' => 'hun',
                'numeric' => '348',
                'currency' =>
                    [
                        'HUF',
                    ],
            ],

            [
                'name' => 'Iceland',
                'alpha2' => 'is',
                'alpha3' => 'isl',
                'numeric' => '352',
                'currency' =>
                    [
                        'ISK',
                    ],
            ],

            [
                'name' => 'India',
                'alpha2' => 'in',
                'alpha3' => 'ind',
                'numeric' => '356',
                'currency' =>
                    [
                        'INR',
                    ],
            ],

            [
                'name' => 'Indonesia',
                'alpha2' => 'id',
                'alpha3' => 'idn',
                'numeric' => '360',
                'currency' =>
                    [
                        'IDR',
                    ],
            ],

            [
                'name' => 'Iran (Islamic Republic of)',
                'alpha2' => 'ir',
                'alpha3' => 'irn',
                'numeric' => '364',
                'currency' =>
                    [
                        'IRR',
                    ],
            ],

            [
                'name' => 'Iraq',
                'alpha2' => 'iq',
                'alpha3' => 'irq',
                'numeric' => '368',
                'currency' =>
                    [
                        'IQD',
                    ],
            ],

            [
                'name' => 'Ireland',
                'alpha2' => 'ie',
                'alpha3' => 'irl',
                'numeric' => '372',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Isle of Man',
                'alpha2' => 'im',
                'alpha3' => 'imn',
                'numeric' => '833',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'Israel',
                'alpha2' => 'il',
                'alpha3' => 'isr',
                'numeric' => '376',
                'currency' =>
                    [
                        'ILS',
                    ],
            ],

            [
                'name' => 'Italy',
                'alpha2' => 'it',
                'alpha3' => 'ita',
                'numeric' => '380',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Jamaica',
                'alpha2' => 'jm',
                'alpha3' => 'jam',
                'numeric' => '388',
                'currency' =>
                    [
                        'JMD',
                    ],
            ],

            [
                'name' => 'Japan',
                'alpha2' => 'jp',
                'alpha3' => 'jpn',
                'numeric' => '392',
                'currency' =>
                    [
                        'JPY',
                    ],
            ],

            [
                'name' => 'Jersey',
                'alpha2' => 'je',
                'alpha3' => 'jey',
                'numeric' => '832',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'Jordan',
                'alpha2' => 'jo',
                'alpha3' => 'jor',
                'numeric' => '400',
                'currency' =>
                    [
                        'JOD',
                    ],
            ],

            [
                'name' => 'Kazakhstan',
                'alpha2' => 'kz',
                'alpha3' => 'kaz',
                'numeric' => '398',
                'currency' =>
                    [
                        'KZT',
                    ],
            ],

            [
                'name' => 'Kenya',
                'alpha2' => 'ke',
                'alpha3' => 'ken',
                'numeric' => '404',
                'currency' =>
                    [
                        'KES',
                    ],
            ],

            [
                'name' => 'Kiribati',
                'alpha2' => 'ki',
                'alpha3' => 'kir',
                'numeric' => '296',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Korea (Democratic People\'s Republic of)',
                'alpha2' => 'kp',
                'alpha3' => 'prk',
                'numeric' => '408',
                'currency' =>
                    [
                        'KPW',
                    ],
            ],

            [
                'name' => 'Korea (Republic of)',
                'alpha2' => 'kr',
                'alpha3' => 'kor',
                'numeric' => '410',
                'currency' =>
                    [
                        'KRW',
                    ],
            ],

            [
                'name' => 'Kuwait',
                'alpha2' => 'kw',
                'alpha3' => 'kwt',
                'numeric' => '414',
                'currency' =>
                    [
                        'KWD',
                    ],
            ],

            [
                'name' => 'Kyrgyzstan',
                'alpha2' => 'kg',
                'alpha3' => 'kgz',
                'numeric' => '417',
                'currency' =>
                    [
                        'KGS',
                    ],
            ],

            [
                'name' => 'Lao People\'s Democratic Republic',
                'alpha2' => 'la',
                'alpha3' => 'lao',
                'numeric' => '418',
                'currency' =>
                    [
                        'LAK',
                    ],
            ],

            [
                'name' => 'Latvia',
                'alpha2' => 'lv',
                'alpha3' => 'lva',
                'numeric' => '428',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Lebanon',
                'alpha2' => 'lb',
                'alpha3' => 'lbn',
                'numeric' => '422',
                'currency' =>
                    [
                        'LBP',
                    ],
            ],

            [
                'name' => 'Lesotho',
                'alpha2' => 'ls',
                'alpha3' => 'lso',
                'numeric' => '426',
                'currency' =>
                    [
                        'LSL',
                        'ZAR',
                    ],
            ],

            [
                'name' => 'Liberia',
                'alpha2' => 'lr',
                'alpha3' => 'lbr',
                'numeric' => '430',
                'currency' =>
                    [
                        'LRD',
                    ],
            ],

            [
                'name' => 'Libya',
                'alpha2' => 'ly',
                'alpha3' => 'lby',
                'numeric' => '434',
                'currency' =>
                    [
                        'LYD',
                    ],
            ],

            [
                'name' => 'Liechtenstein',
                'alpha2' => 'li',
                'alpha3' => 'lie',
                'numeric' => '438',
                'currency' =>
                    [
                        'CHF',
                    ],
            ],

            [
                'name' => 'Lithuania',
                'alpha2' => 'lt',
                'alpha3' => 'ltu',
                'numeric' => '440',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Luxembourg',
                'alpha2' => 'lu',
                'alpha3' => 'lux',
                'numeric' => '442',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Macao',
                'alpha2' => 'mo',
                'alpha3' => 'mac',
                'numeric' => '446',
                'currency' =>
                    [
                        'MOP',
                    ],
            ],

            [
                'name' => 'North Macedonia',
                'alpha2' => 'mk',
                'alpha3' => 'mkd',
                'numeric' => '807',
                'currency' =>
                    [
                        'MKD',
                    ],
            ],

            [
                'name' => 'Madagascar',
                'alpha2' => 'mg',
                'alpha3' => 'mdg',
                'numeric' => '450',
                'currency' =>
                    [
                        'MGA',
                    ],
            ],

            [
                'name' => 'Malawi',
                'alpha2' => 'mw',
                'alpha3' => 'mwi',
                'numeric' => '454',
                'currency' =>
                    [
                        'MWK',
                    ],
            ],

            [
                'name' => 'Malaysia',
                'alpha2' => 'my',
                'alpha3' => 'mys',
                'numeric' => '458',
                'currency' =>
                    [
                        'MYR',
                    ],
            ],

            [
                'name' => 'Maldives',
                'alpha2' => 'mv',
                'alpha3' => 'mdv',
                'numeric' => '462',
                'currency' =>
                    [
                        'MVR',
                    ],
            ],

            [
                'name' => 'Mali',
                'alpha2' => 'ml',
                'alpha3' => 'mli',
                'numeric' => '466',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Malta',
                'alpha2' => 'mt',
                'alpha3' => 'mlt',
                'numeric' => '470',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Marshall Islands',
                'alpha2' => 'mh',
                'alpha3' => 'mhl',
                'numeric' => '584',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Martinique',
                'alpha2' => 'mq',
                'alpha3' => 'mtq',
                'numeric' => '474',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Mauritania',
                'alpha2' => 'mr',
                'alpha3' => 'mrt',
                'numeric' => '478',
                'currency' =>
                    [
                        'MRO',
                    ],
            ],

            [
                'name' => 'Mauritius',
                'alpha2' => 'mu',
                'alpha3' => 'mus',
                'numeric' => '480',
                'currency' =>
                    [
                        'MUR',
                    ],
            ],

            [
                'name' => 'Mayotte',
                'alpha2' => 'yt',
                'alpha3' => 'myt',
                'numeric' => '175',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Mexico',
                'alpha2' => 'mx',
                'alpha3' => 'mex',
                'numeric' => '484',
                'currency' =>
                    [
                        'MXN',
                    ],
            ],

            [
                'name' => 'Micronesia (Federated States of)',
                'alpha2' => 'fm',
                'alpha3' => 'fsm',
                'numeric' => '583',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Moldova (Republic of)',
                'alpha2' => 'md',
                'alpha3' => 'mda',
                'numeric' => '498',
                'currency' =>
                    [
                        'MDL',
                    ],
            ],

            [
                'name' => 'Monaco',
                'alpha2' => 'mc',
                'alpha3' => 'mco',
                'numeric' => '492',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Mongolia',
                'alpha2' => 'mn',
                'alpha3' => 'mng',
                'numeric' => '496',
                'currency' =>
                    [
                        'MNT',
                    ],
            ],

            [
                'name' => 'Montenegro',
                'alpha2' => 'me',
                'alpha3' => 'mne',
                'numeric' => '499',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Montserrat',
                'alpha2' => 'ms',
                'alpha3' => 'msr',
                'numeric' => '500',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Morocco',
                'alpha2' => 'ma',
                'alpha3' => 'mar',
                'numeric' => '504',
                'currency' =>
                    [
                        'MAD',
                    ],
            ],

            [
                'name' => 'Mozambique',
                'alpha2' => 'mz',
                'alpha3' => 'moz',
                'numeric' => '508',
                'currency' =>
                    [
                        'MZN',
                    ],
            ],

            [
                'name' => 'Myanmar',
                'alpha2' => 'mm',
                'alpha3' => 'mmr',
                'numeric' => '104',
                'currency' =>
                    [
                        'MMK',
                    ],
            ],

            [
                'name' => 'Namibia',
                'alpha2' => 'na',
                'alpha3' => 'nam',
                'numeric' => '516',
                'currency' =>
                    [
                        'NAD',
                        'ZAR',
                    ],
            ],

            [
                'name' => 'Nauru',
                'alpha2' => 'nr',
                'alpha3' => 'nru',
                'numeric' => '520',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Nepal',
                'alpha2' => 'np',
                'alpha3' => 'npl',
                'numeric' => '524',
                'currency' =>
                    [
                        'NPR',
                    ],
            ],

            [
                'name' => 'Netherlands',
                'alpha2' => 'nl',
                'alpha3' => 'nld',
                'numeric' => '528',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'New Caledonia',
                'alpha2' => 'nc',
                'alpha3' => 'ncl',
                'numeric' => '540',
                'currency' =>
                    [
                        'XPF',
                    ],
            ],

            [
                'name' => 'New Zealand',
                'alpha2' => 'nz',
                'alpha3' => 'nzl',
                'numeric' => '554',
                'currency' =>
                    [
                        'NZD',
                    ],
            ],

            [
                'name' => 'Nicaragua',
                'alpha2' => 'ni',
                'alpha3' => 'nic',
                'numeric' => '558',
                'currency' =>
                    [
                        'NIO',
                    ],
            ],

            [
                'name' => 'Niger',
                'alpha2' => 'ne',
                'alpha3' => 'ner',
                'numeric' => '562',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Nigeria',
                'alpha2' => 'ng',
                'alpha3' => 'nga',
                'numeric' => '566',
                'currency' =>
                    [
                        'NGN',
                    ],
            ],

            [
                'name' => 'Niue',
                'alpha2' => 'nu',
                'alpha3' => 'niu',
                'numeric' => '570',
                'currency' =>
                    [
                        'NZD',
                    ],
            ],

            [
                'name' => 'Norfolk Island',
                'alpha2' => 'nf',
                'alpha3' => 'nfk',
                'numeric' => '574',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Northern Mariana Islands',
                'alpha2' => 'mp',
                'alpha3' => 'mnp',
                'numeric' => '580',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Norway',
                'alpha2' => 'no',
                'alpha3' => 'nor',
                'numeric' => '578',
                'currency' =>
                    [
                        'NOK',
                    ],
            ],

            [
                'name' => 'Oman',
                'alpha2' => 'om',
                'alpha3' => 'omn',
                'numeric' => '512',
                'currency' =>
                    [
                        'OMR',
                    ],
            ],

            [
                'name' => 'Pakistan',
                'alpha2' => 'pk',
                'alpha3' => 'pak',
                'numeric' => '586',
                'currency' =>
                    [
                        'PKR',
                    ],
            ],

            [
                'name' => 'Palau',
                'alpha2' => 'pw',
                'alpha3' => 'plw',
                'numeric' => '585',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Palestine, State of',
                'alpha2' => 'ps',
                'alpha3' => 'pse',
                'numeric' => '275',
                'currency' =>
                    [
                        'ILS',
                    ],
            ],

            [
                'name' => 'Panama',
                'alpha2' => 'pa',
                'alpha3' => 'pan',
                'numeric' => '591',
                'currency' =>
                    [
                        'PAB',
                    ],
            ],

            [
                'name' => 'Papua New Guinea',
                'alpha2' => 'pg',
                'alpha3' => 'png',
                'numeric' => '598',
                'currency' =>
                    [
                        'PGK',
                    ],
            ],

            [
                'name' => 'Paraguay',
                'alpha2' => 'py',
                'alpha3' => 'pry',
                'numeric' => '600',
                'currency' =>
                    [
                        'PYG',
                    ],
            ],

            [
                'name' => 'Peru',
                'alpha2' => 'pe',
                'alpha3' => 'per',
                'numeric' => '604',
                'currency' =>
                    [
                        'PEN',
                    ],
            ],

            [
                'name' => 'Philippines',
                'alpha2' => 'ph',
                'alpha3' => 'phl',
                'numeric' => '608',
                'currency' =>
                    [
                        'PHP',
                    ],
            ],

            [
                'name' => 'Pitcairn',
                'alpha2' => 'pn',
                'alpha3' => 'pcn',
                'numeric' => '612',
                'currency' =>
                    [
                        'NZD',
                    ],
            ],

            [
                'name' => 'Poland',
                'alpha2' => 'pl',
                'alpha3' => 'pol',
                'numeric' => '616',
                'currency' =>
                    [
                        'PLN',
                    ],
            ],

            [
                'name' => 'Portugal',
                'alpha2' => 'pt',
                'alpha3' => 'prt',
                'numeric' => '620',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Puerto Rico',
                'alpha2' => 'pr',
                'alpha3' => 'pri',
                'numeric' => '630',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Qatar',
                'alpha2' => 'qa',
                'alpha3' => 'qat',
                'numeric' => '634',
                'currency' =>
                    [
                        'QAR',
                    ],
            ],

            [
                'name' => 'Réunion',
                'alpha2' => 're',
                'alpha3' => 'reu',
                'numeric' => '638',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Romania',
                'alpha2' => 'ro',
                'alpha3' => 'rou',
                'numeric' => '642',
                'currency' =>
                    [
                        'RON',
                    ],
            ],

            [
                'name' => 'Russian Federation',
                'alpha2' => 'ru',
                'alpha3' => 'rus',
                'numeric' => '643',
                'currency' =>
                    [
                        'RUB',
                    ],
            ],

            [
                'name' => 'Rwanda',
                'alpha2' => 'rw',
                'alpha3' => 'rwa',
                'numeric' => '646',
                'currency' =>
                    [
                        'RWF',
                    ],
            ],

            [
                'name' => 'Saint Barthélemy',
                'alpha2' => 'bl',
                'alpha3' => 'blm',
                'numeric' => '652',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Saint Helena, Ascension and Tristan da Cunha',
                'alpha2' => 'sh',
                'alpha3' => 'shn',
                'numeric' => '654',
                'currency' =>
                    [
                        'SHP',
                    ],
            ],

            [
                'name' => 'Saint Kitts and Nevis',
                'alpha2' => 'kn',
                'alpha3' => 'kna',
                'numeric' => '659',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Saint Lucia',
                'alpha2' => 'lc',
                'alpha3' => 'lca',
                'numeric' => '662',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Saint Martin (French part)',
                'alpha2' => 'mf',
                'alpha3' => 'maf',
                'numeric' => '663',
                'currency' =>
                    [
                        'EUR',
                        'USD',
                    ],
            ],

            [
                'name' => 'Saint Pierre and Miquelon',
                'alpha2' => 'pm',
                'alpha3' => 'spm',
                'numeric' => '666',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Saint Vincent and the Grenadines',
                'alpha2' => 'vc',
                'alpha3' => 'vct',
                'numeric' => '670',
                'currency' =>
                    [
                        'XCD',
                    ],
            ],

            [
                'name' => 'Samoa',
                'alpha2' => 'ws',
                'alpha3' => 'wsm',
                'numeric' => '882',
                'currency' =>
                    [
                        'WST',
                    ],
            ],

            [
                'name' => 'San Marino',
                'alpha2' => 'sm',
                'alpha3' => 'smr',
                'numeric' => '674',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Sao Tome and Principe',
                'alpha2' => 'st',
                'alpha3' => 'stp',
                'numeric' => '678',
                'currency' =>
                    [
                        'STD',
                    ],
            ],

            [
                'name' => 'Saudi Arabia',
                'alpha2' => 'sa',
                'alpha3' => 'sau',
                'numeric' => '682',
                'currency' =>
                    [
                        'SAR',
                    ],
            ],

            [
                'name' => 'Senegal',
                'alpha2' => 'sn',
                'alpha3' => 'sen',
                'numeric' => '686',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Serbia',
                'alpha2' => 'rs',
                'alpha3' => 'srb',
                'numeric' => '688',
                'currency' =>
                    [
                        'RSD',
                    ],
            ],

            [
                'name' => 'Seychelles',
                'alpha2' => 'sc',
                'alpha3' => 'syc',
                'numeric' => '690',
                'currency' =>
                    [
                        'SCR',
                    ],
            ],

            [
                'name' => 'Sierra Leone',
                'alpha2' => 'sl',
                'alpha3' => 'sle',
                'numeric' => '694',
                'currency' =>
                    [
                        'SLL',
                    ],
            ],

            [
                'name' => 'Singapore',
                'alpha2' => 'sg',
                'alpha3' => 'sgp',
                'numeric' => '702',
                'currency' =>
                    [
                        'SGD',
                    ],
            ],

            [
                'name' => 'Sint Maarten (Dutch part)',
                'alpha2' => 'sx',
                'alpha3' => 'sxm',
                'numeric' => '534',
                'currency' =>
                    [
                        'ANG',
                    ],
            ],

            [
                'name' => 'Slovakia',
                'alpha2' => 'sk',
                'alpha3' => 'svk',
                'numeric' => '703',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Slovenia',
                'alpha2' => 'si',
                'alpha3' => 'svn',
                'numeric' => '705',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Solomon Islands',
                'alpha2' => 'sb',
                'alpha3' => 'slb',
                'numeric' => '090',
                'currency' =>
                    [
                        'SBD',
                    ],
            ],

            [
                'name' => 'Somalia',
                'alpha2' => 'so',
                'alpha3' => 'som',
                'numeric' => '706',
                'currency' =>
                    [
                        'SOS',
                    ],
            ],

            [
                'name' => 'South Africa',
                'alpha2' => 'za',
                'alpha3' => 'zaf',
                'numeric' => '710',
                'currency' =>
                    [
                        'ZAR',
                    ],
            ],

            [
                'name' => 'South Georgia and the South Sandwich Islands',
                'alpha2' => 'gs',
                'alpha3' => 'sgs',
                'numeric' => '239',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'South Sudan',
                'alpha2' => 'ss',
                'alpha3' => 'ssd',
                'numeric' => '728',
                'currency' =>
                    [
                        'SSP',
                    ],
            ],

            [
                'name' => 'Spain',
                'alpha2' => 'es',
                'alpha3' => 'esp',
                'numeric' => '724',
                'currency' =>
                    [
                        'EUR',
                    ],
            ],

            [
                'name' => 'Sri Lanka',
                'alpha2' => 'lk',
                'alpha3' => 'lka',
                'numeric' => '144',
                'currency' =>
                    [
                        'LKR',
                    ],
            ],

            [
                'name' => 'Sudan',
                'alpha2' => 'sd',
                'alpha3' => 'sdn',
                'numeric' => '729',
                'currency' =>
                    [
                        'SDG',
                    ],
            ],

            [
                'name' => 'Suriname',
                'alpha2' => 'sr',
                'alpha3' => 'sur',
                'numeric' => '740',
                'currency' =>
                    [
                        'SRD',
                    ],
            ],

            [
                'name' => 'Svalbard and Jan Mayen',
                'alpha2' => 'sj',
                'alpha3' => 'sjm',
                'numeric' => '744',
                'currency' =>
                    [
                        'NOK',
                    ],
            ],

            [
                'name' => 'Sweden',
                'alpha2' => 'se',
                'alpha3' => 'swe',
                'numeric' => '752',
                'currency' =>
                    [
                        'SEK',
                    ],
            ],

            [
                'name' => 'Switzerland',
                'alpha2' => 'ch',
                'alpha3' => 'che',
                'numeric' => '756',
                'currency' =>
                    [
                        'CHF',
                    ],
            ],

            [
                'name' => 'Syrian Arab Republic',
                'alpha2' => 'sy',
                'alpha3' => 'syr',
                'numeric' => '760',
                'currency' =>
                    [
                        'SYP',
                    ],
            ],

            [
                'name' => 'Taiwan (Province of China)',
                'alpha2' => 'tw',
                'alpha3' => 'twn',
                'numeric' => '158',
                'currency' =>
                    [
                        'TWD',
                    ],
            ],

            [
                'name' => 'Tajikistan',
                'alpha2' => 'tj',
                'alpha3' => 'tjk',
                'numeric' => '762',
                'currency' =>
                    [
                        'TJS',
                    ],
            ],

            [
                'name' => 'Tanzania, United Republic of',
                'alpha2' => 'tz',
                'alpha3' => 'tza',
                'numeric' => '834',
                'currency' =>
                    [
                        'TZS',
                    ],
            ],

            [
                'name' => 'Thailand',
                'alpha2' => 'th',
                'alpha3' => 'tha',
                'numeric' => '764',
                'currency' =>
                    [
                        'THB',
                    ],
            ],

            [
                'name' => 'Timor-Leste',
                'alpha2' => 'tl',
                'alpha3' => 'tls',
                'numeric' => '626',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Togo',
                'alpha2' => 'tg',
                'alpha3' => 'tgo',
                'numeric' => '768',
                'currency' =>
                    [
                        'XOF',
                    ],
            ],

            [
                'name' => 'Tokelau',
                'alpha2' => 'tk',
                'alpha3' => 'tkl',
                'numeric' => '772',
                'currency' =>
                    [
                        'NZD',
                    ],
            ],

            [
                'name' => 'Tonga',
                'alpha2' => 'to',
                'alpha3' => 'ton',
                'numeric' => '776',
                'currency' =>
                    [
                        'TOP',
                    ],
            ],

            [
                'name' => 'Trinidad and Tobago',
                'alpha2' => 'tt',
                'alpha3' => 'tto',
                'numeric' => '780',
                'currency' =>
                    [
                        'TTD',
                    ],
            ],

            [
                'name' => 'Tunisia',
                'alpha2' => 'tn',
                'alpha3' => 'tun',
                'numeric' => '788',
                'currency' =>
                    [
                        'TND',
                    ],
            ],

            [
                'name' => 'Turkey',
                'alpha2' => 'tr',
                'alpha3' => 'tur',
                'numeric' => '792',
                'currency' =>
                    [
                        'TRY',
                    ],
            ],

            [
                'name' => 'Turkmenistan',
                'alpha2' => 'tm',
                'alpha3' => 'tkm',
                'numeric' => '795',
                'currency' =>
                    [
                        'TMT',
                    ],
            ],

            [
                'name' => 'Turks and Caicos Islands',
                'alpha2' => 'tc',
                'alpha3' => 'tca',
                'numeric' => '796',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Tuvalu',
                'alpha2' => 'tv',
                'alpha3' => 'tuv',
                'numeric' => '798',
                'currency' =>
                    [
                        'AUD',
                    ],
            ],

            [
                'name' => 'Uganda',
                'alpha2' => 'ug',
                'alpha3' => 'uga',
                'numeric' => '800',
                'currency' =>
                    [
                        'UGX',
                    ],
            ],

            [
                'name' => 'Ukraine',
                'alpha2' => 'ua',
                'alpha3' => 'ukr',
                'numeric' => '804',
                'currency' =>
                    [
                        'UAH',
                    ],
            ],

            [
                'name' => 'United Arab Emirates',
                'alpha2' => 'ae',
                'alpha3' => 'are',
                'numeric' => '784',
                'currency' =>
                    [
                        'AED',
                    ],
            ],

            [
                'name' => 'United Kingdom of Great Britain and Northern Ireland',
                'alpha2' => 'gb',
                'alpha3' => 'gbr',
                'numeric' => '826',
                'currency' =>
                    [
                        'GBP',
                    ],
            ],

            [
                'name' => 'United States of America',
                'alpha2' => 'us',
                'alpha3' => 'usa',
                'numeric' => '840',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'United States Minor Outlying Islands',
                'alpha2' => 'um',
                'alpha3' => 'umi',
                'numeric' => '581',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Uruguay',
                'alpha2' => 'uy',
                'alpha3' => 'ury',
                'numeric' => '858',
                'currency' =>
                    [
                        'UYU',
                    ],
            ],

            [
                'name' => 'Uzbekistan',
                'alpha2' => 'uz',
                'alpha3' => 'uzb',
                'numeric' => '860',
                'currency' =>
                    [
                        'UZS',
                    ],
            ],

            [
                'name' => 'Vanuatu',
                'alpha2' => 'vu',
                'alpha3' => 'vut',
                'numeric' => '548',
                'currency' =>
                    [
                        'VUV',
                    ],
            ],

            [
                'name' => 'Venezuela (Bolivarian Republic of)',
                'alpha2' => 've',
                'alpha3' => 'ven',
                'numeric' => '862',
                'currency' =>
                    [
                        'VEF',
                    ],
            ],

            [
                'name' => 'Viet Nam',
                'alpha2' => 'vn',
                'alpha3' => 'vnm',
                'numeric' => '704',
                'currency' =>
                    [
                        'VND',
                    ],
            ],

            [
                'name' => 'Virgin Islands (British)',
                'alpha2' => 'vg',
                'alpha3' => 'vgb',
                'numeric' => '092',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Virgin Islands (U.S.)',
                'alpha2' => 'vi',
                'alpha3' => 'vir',
                'numeric' => '850',
                'currency' =>
                    [
                        'USD',
                    ],
            ],

            [
                'name' => 'Wallis and Futuna',
                'alpha2' => 'wf',
                'alpha3' => 'wlf',
                'numeric' => '876',
                'currency' =>
                    [
                        'XPF',
                    ],
            ],

            [
                'name' => 'Western Sahara',
                'alpha2' => 'eh',
                'alpha3' => 'esh',
                'numeric' => '732',
                'currency' =>
                    [
                        'MAD',
                    ],
            ],

            [
                'name' => 'Yemen',
                'alpha2' => 'ye',
                'alpha3' => 'yem',
                'numeric' => '887',
                'currency' =>
                    [
                        'YER',
                    ],
            ],

            [
                'name' => 'Zambia',
                'alpha2' => 'zm',
                'alpha3' => 'zmb',
                'numeric' => '894',
                'currency' =>
                    [
                        'ZMW',
                    ],
            ],

            [
                'name' => 'Zimbabwe',
                'alpha2' => 'zw',
                'alpha3' => 'zwe',
                'numeric' => '716',
                'currency' =>
                    [
                        'BWP',
                        'EUR',
                        'GBP',
                        'USD',
                        'ZAR',
                    ],
            ],
        ];

    }
    
}