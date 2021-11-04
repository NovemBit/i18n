<?php

/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type;

use DOMDocument;
use DOMElement;
use DOMText;
use DOMXPath;
use NovemBit\i18n\component\localization\languages\Languages;
use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\translation\models\TranslationDataMapper;
use NovemBit\i18n\component\translation\Translation;
use Psr\SimpleCache\CacheInterface;

/**
 * HTML type for translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class HtmlTranslator extends XmlTranslator implements interfaces\HTML
{
    /**
     * {@inheritdoc}
     * */
    public string $name = 'html';

    /**
     * Xpath Query for parser
     *
     * @var string
     * */
    public string $parser_query = ".//*[not(ancestor-or-self::*[@translate='no']) and (text() or @*)]";

    /**
     * Title tag template
     * Callable function params $translate, $language, $country, $region
     *
     * @var string|callable
     * */
    public $title_tag_template = '{translate} | {language_name}, {country}';

    /**
     * Model class name of ActiveRecord
     * */
    public string|\NovemBit\i18n\component\translation\models\TranslationDataMapper $model_class = models\HTML::class;

    protected int $parser_type = \NovemBit\i18n\system\parsers\interfaces\XML::HTML;

    public function __construct(
        private Localization $localization,
        private Languages $languages,
        Translation $translation,
        TranslationDataMapper $translation_data_mapper,
        private CacheInterface $cache,
        private TypeTranslatorFactory $type_factory
    ) {
        parent::__construct(
            $this->localization,
            $translation,
            $this->cache,
            $translation_data_mapper,
            $this->type_factory
        );
    }

    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param  string  $xml  Html content
     * @param  string  $language  Language code
     *
     * @return \NovemBit\i18n\system\parsers\XML
     */
    protected function getParser(
        string $xml,
        string $from_language,
        string $to_language
    ): \NovemBit\i18n\system\parsers\XML {
        $this->addAfterParseCallback(
            function ($xpath) use ($to_language) {
                /**
                 * Setting var types
                 *
                 * @var DOMXPath $xpath
                 * @var DOMElement $html
                 */
                $html = $xpath->query('//html')->item(0);

                if ( ! $html) {
                    return;
                }

                $html->setAttribute('lang', $to_language);
                $dir = $this->languages->getLanguageDirectionByCode($to_language);

                /**
                 * Set html dir="rtl" attribute
                 * When language dir set rtl
                 * */
                if ($dir === 'rtl') {
                    $html->setAttribute('dir', 'rtl');
                }
            }
        );

        $this->addAfterParseCallback(
            function (DOMXPath $xpath, DOMDocument $dom) use ($from_language, $to_language) {
                /**
                 * Setting var types
                 *
                 * @var DOMXPath $xpath
                 * @var DOMText $title
                 */
                $title = $xpath->query('//html/head/title/text()')->item(0);
                if ($title !== null) {
                    $language_name = $this->languages->getLanguageNameByCode($to_language);
                    $country_name  = $this->getTranslation()->getCountry();
                    $region_name   = $this->getTranslation()->getRegion();

                    $translator = $this->type_factory->getTypeTranslator('text');

                    if ($this->isCacheResult() === false) {
                        $translator->setCacheResult(false);
                    }

                    $_translations = $translator
                        ->translate(
                            [$language_name, $country_name, $region_name],
                            $from_language,
                            [$to_language],
                        );

                    $language_native_name = $_translations[$language_name][$to_language]
                                            ?? $language_name;

                    $country_native_name = $_translations[$country_name][$to_language]
                                           ?? $country_name;

                    $region_native_name = $_translations[$region_name][$to_language]
                                          ?? $region_name;

                    if (is_callable($this->title_tag_template)) {
                        $title->data = call_user_func(
                            $this->title_tag_template,
                            [
                                'translate'       => $title->data,
                                'language_code'   => $to_language,
                                'language_name'   => $language_name,
                                'language_native' => $language_native_name,
                                'country'         => $country_name,
                                'country_native'  => $country_native_name,
                                'region'          => $region_name,
                                'region_native'   => $region_native_name
                            ]
                        );
                    } else {
                        $title->data = strtr(
                            $this->title_tag_template,
                            [
                                '{translate}'       => $title->data,
                                '{language_code}'   => $to_language,
                                '{language_name}'   => $language_name,
                                '{language_native}' => $language_native_name,
                                '{country}'         => $country_name,
                                '{country_native}'  => $country_native_name,
                                '{region}'          => $region_name,
                                '{region_native}'   => $region_native_name
                            ]
                        );
                    }
                }
            }
        );


        return parent::getParser($xml, $from_language, $to_language);
    }

    public function getDbId(): int
    {
        return 3;
    }
}
