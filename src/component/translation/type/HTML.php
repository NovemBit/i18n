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

use DOMAttr;
use DOMElement;
use DOMText;
use DOMXPath;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\Translator;
use NovemBit\i18n\models\exceptions\ActiveRecordException;
use NovemBit\i18n\system\parsers\xml\Rule;


/**
 * HTML type for translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class HTML extends XML implements interfaces\HTML
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'html';

    /**
     * Xpath Query for parser
     *
     * @var string
     * */
    public $parser_query = ".//*[not(ancestor-or-self::*[@translate='no']) and (text() or @*)]";

    /**
     * Title tag template
     * Callable function params $translate, $language, $country, $region
     *
     * @var string|callable
     * */
    public $title_tag_template = "{translate} | {language_name}, {country}";

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\HTML::class;

    protected $parser_type = \NovemBit\i18n\system\parsers\XML::HTML;

    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param string $xml      Html content
     * @param string $language Language code
     *
     * @return \NovemBit\i18n\system\parsers\XML
     */
    protected function getParser(
        string $xml,
        string $language
    ): \NovemBit\i18n\system\parsers\XML {

        $this->addAfterParseCallback(
            function ($xpath) use ($language) {
                /**
                 * Setting var types
                 *
                 * @var DOMXPath $xpath
                 * @var DOMAttr $lang
                 */
                $lang = $xpath->query('//html/@lang')->item(0);
                if ($lang !== null) {
                    $lang->value = $language;
                }
            }
        );

        $this->addAfterParseCallback(
            function (DOMXPath $xpath, \DOMDocument $dom) use ($language) {
                /**
                 * Setting var types
                 *
                 * @var DOMXPath $xpath
                 * @var DOMText $title
                 */
                $title = $xpath->query('//html/head/title/text()')->item(0);
                if ($title !== null) {

                    $language_name = $this->context->context->languages
                        ->getLanguageNameByCode($language);
                    $country_name = $this->context->getCountry();
                    $region_name = $this->context->getRegion();

                    $_translations =  $this
                        ->getTranslation()
                        ->text
                        ->translate([$language_name, $country_name, $region_name]);

                    $language_native_name = $_translations[$language_name][$language]
                        ?? $language_name;

                    $country_native_name = $_translations[$country_name][$language]
                        ?? $country_name;

                    $region_native_name = $_translations[$region_name][$language]
                        ?? $country_name;

                    if (is_callable($this->title_tag_template)) {
                        $title->data = call_user_func(
                            $this->title_tag_template,
                            [
                                'translate' => $title->data,
                                'language_code' => $language,
                                'language_name' => $language_name,
                                'language_native' => $language_native_name,
                                'country' => $country_name,
                                'country_native' => $country_native_name,
                                'region' => $region_name,
                                'region_native' => $region_native_name
                            ]
                        );
                    } else {
                        $title->data = strtr(
                            $this->title_tag_template, [
                                '{translate}' => $title->data,
                                '{language_code}' => $language,
                                '{language_name}' => $language_name,
                                '{language_native}' => $language_native_name,
                                '{country}' => $country_name,
                                '{country_native}' => $country_native_name,
                                '{region}' => $region_name,
                                '{region_native}' => $region_native_name
                            ]
                        );
                    }
                }

            }
        );


        return parent::getParser($xml, $language);
    }


}