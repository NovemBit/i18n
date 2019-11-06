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
    public $parser_query=".//*[not(ancestor-or-self::*[@translate='no']) and (text() or @*)]";

    /**
     * Title tag template
     * Callable function params $translate, $language, $country, $region
     *
     * @var string|callable
     * */
    public $title_tag_template = "{translate} | {country}, {language_name}";

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

        $this->addBeforeParseCallback(
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
                        ->getAcceptLanguages(true)[$language]['name'];

                    if (is_callable($this->title_tag_template)) {
                        $title->data = call_user_func(
                            $this->title_tag_template,
                            [
                                'translate'=>$title->data,
                                'language_code'=>$language,
                                'language_name'=>$language_name,
                                'country'=>$this->context->getCountry(),
                                'region'=>$this->context->getRegion()
                            ]
                        );
                    } else {
                        $title->data = strtr(
                            $this->title_tag_template, [
                                '{translate}' => $title->data,
                                '{language_name}' =>$language_name,
                                '{language_code}' => $language,
                                '{country}' => $this->context->getCountry(),
                                '{region}' => $this->context->getRegion()
                            ]
                        );
                    }
                }


            }
        );


        return parent::getParser($xml, $language);
    }


}