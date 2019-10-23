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
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\Translation;
use NovemBit\i18n\system\parsers\html\Rule;


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
class HTML extends Type
{

    /**
     * Name of current type
     *
     * @var string
     * */
    const NAME = 'html';

    /**
     * {@inheritdoc}
     * */
    public $type = 3;

    /**
     * Fetched fields to translate
     *
     * @var array
     * */
    public $fields_to_translate = [];

    /**
     * Show helper attributes that contains
     * All information about current node and child Text/Attr nodes
     *
     * @var bool
     * */
    public $helper_attributes = false;

    /**
     * To translate
     *
     * @var array
     * */
    private $_to_translate = [];

    /**
     * Translated contents
     *
     * @var array
     * */
    private $_translations = [];

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\HTML::class;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function init() : void
    {

    }

    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param string $html Html content
     *
     * @see \NovemBit\i18n\system\parsers\HTML
     *
     * @return \NovemBit\i18n\system\parsers\HTML
     */
    private function _getHtmlParser(string $html)
    : \NovemBit\i18n\system\parsers\HTML
    {
        $parser = new \NovemBit\i18n\system\parsers\HTML();

        foreach ($this->fields_to_translate as $field) {
            $text = isset($field['text']) ? $field['text'] : 'text';
            $attrs = isset($field['attrs']) ? $field['attrs'] : [];

            $rule = new Rule(
                $field['rule']['tags'] ?? null,
                $field['rule']['attrs'] ?? null,
                $field['rule']['texts'] ?? null,
                $field['rule']['mode'] ?? Rule::IN
            );

            $parser->addTranslateField($rule, $text, $attrs);
        }

        $parser->load($html);

        return $parser;
    }


    /**
     * Doing translate method
     * Getting node values from two type of DOMNode
     *
     * * DOMText - text content of parent node
     * * DOMAttr - attrs values of parent node
     *
     * Then using callbacks for decode html entities
     * And send to translation:
     * Using custom type of translation for each type of node
     *
     * @param array $html_list list of translatable HTML strings
     *
     * @see DOMText
     * @see DOMAttr
     *
     * @return mixed
     * @throws TranslationException
     */
    public function doTranslate(array $html_list) : array
    {
        $languages = $this->context->getLanguages();

        $result = [];

        $this->_translations = [];

        $_parsed_dom = [];

        /*
         * Finding translatable node values and attributes
         * */
        foreach ($html_list as $key => $html) {

            $_parsed_dom[$key] = $this->_getHtmlParser($html);

            $_parsed_dom[$key]->fetch(
                function (&$node, $type) {
                    /**
                     * Callback for Text nodes
                     *
                     * @todo Runtime debugging (important)
                     *
                     * @var DOMText $node Text node
                     */
                    $node->data = htmlspecialchars_decode(
                        $node->data,
                        ENT_QUOTES | ENT_HTML401
                    );

                    $this->_to_translate[$type][] = $node->data;
                },
                function (&$node, $type) {
                    /**
                     * Callback for Attribute nodes
                     *
                     * @todo Runtime debugging (important)
                     *
                     * @var DOMAttr $node
                     */
                    /*$node->value = htmlspecialchars_decode(
                        $node->value,
                        ENT_QUOTES | ENT_HTML401
                    );*/

                    $this->_to_translate[$type][] = $node->value;
                }
            );
        }

        foreach ($this->_to_translate as $type => $texts) {
            $this->_translations[$type] = $this->context->{$type}->translate($texts);
        }

        /**
         * Replace html node values to
         * Translated values
         * */
        foreach ($html_list as $key => $html) {

            foreach ($languages as $language) {

                $_parsed_dom[$key]->fetch(
                    function (&$node, $type, $rule) use ($language) {
                        /**
                         * Callback for Text node
                         *
                         * @var DOMText $node
                         * @var DOMElement $parent
                         * @var Rule $rule
                         */
                        $translate =  $this->_translations
                            [$type][$node->data][$language]
                            ?? null;

                        /**
                         * Enable helper attributes
                         * */
                        if ($this->helper_attributes == true) {
                            $parent = $node->parentNode;

                            if ($parent->hasAttribute(
                                $this->context->context->prefix . '-text'
                            )
                            ) {
                                $text = json_decode(
                                    $parent->getAttribute(
                                        $this->context->context->prefix . '-text'
                                    ),
                                    true
                                );
                            } else {
                                $text = [];
                            }

                            if ($translate !== null) {
                                $text[] = [
                                    $node->data,
                                    $translate,
                                    $type
                                ];
                                $parent->setAttribute(
                                    $this->context->context->prefix . '-text',
                                    json_encode($text)
                                );
                            }
                        }

                        $node->data = $translate ?? $node->data;
                    },
                    /*
                     * Callback for Attribute nodes
                     * */
                    function (&$node, $type) use ($language) {
                        /**
                         * Callback for Text node
                         *
                         * @var DOMAttr $node
                         * @var DOMElement $parent
                         * @var Rule $rule
                         */

                        $translate = $this->_translations
                            [$type][$node->value][$language]
                            ?? null;

                        /**
                         * Enable helper attributes
                         * */
                        if ($this->helper_attributes == true) {

                            $parent = $node->parentNode;
                            if ($parent->hasAttribute(
                                $this->context->context->prefix . '-attr'
                            )
                            ) {
                                $attr = json_decode(
                                    $parent->getAttribute(
                                        $this->context->context->prefix . '-attr'
                                    ),
                                    true
                                );
                            } else {
                                $attr = [];
                            }
                            if ($translate !== null) {
                                $attr[$node->name] = [
                                    $node->value,
                                    $translate,
                                    $type
                                ];
                                $parent->setAttribute(
                                    $this->context->context->prefix . '-attr',
                                    json_encode($attr)
                                );
                            }
                        }

                        $node->value = htmlspecialchars($translate) ?? $node->value;
                    }
                );

                $result[$html][$language] = $_parsed_dom[$key]->save();
            }
        }

        return $result;
    }
}