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

namespace NovemBit\i18n\component\translation\Type;

use DOMAttr;
use DOMElement;
use DOMText;
use Exception;
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
     * Html parser
     *
     * @var \NovemBit\i18n\system\parsers\HTML
     * */
    private $_html_parser;

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
     * {@inheritdoc}
     *
     * @return void
     */
    public function init()
    {

        $this->setHtmlParser(new \NovemBit\i18n\system\parsers\HTML());

        foreach ($this->fields_to_translate as $field) {
            $text = isset($field['text']) ? $field['text'] : 'text';
            $attrs = isset($field['attrs']) ? $field['attrs'] : [];

            $rule = new Rule(
                $field['rule']['tags'] ?? null,
                $field['rule']['attrs'] ?? null,
                $field['rule']['texts'] ?? null,
                $field['rule']['mode'] ?? 'in'
            );

            $this->getHtmlParser()->addTranslateField($rule, $text, $attrs);
        }
    }

    /**
     * Get Html parser
     *
     * @return \NovemBit\i18n\system\parsers\HTML
     */
    public function getHtmlParser()
    {
        return $this->_html_parser;
    }

    /**
     * Set Html Parser
     *
     * @param \NovemBit\i18n\system\parsers\HTML $_html_parser Html parser
     *
     * @return void
     */
    public function setHtmlParser(\NovemBit\i18n\system\parsers\HTML $_html_parser)
    {
        $this->_html_parser = $_html_parser;
    }

    /**
     * Doing translate method
     *
     * @param array $html_list list of translatable HTML strings
     *
     * @return mixed
     * @throws Exception
     */
    public function doTranslate(array $html_list)
    {
        $languages = $this->context->getLanguages();

        $result = [];

        $this->_translations = [];

        /*
         * Finding translatable node values and attributes
         * */
        foreach ($html_list as $html) {

            $this->getHtmlParser()->load($html);

            $this->getHtmlParser()->fetch(
                function (&$node, $type) {
                    /**
                     * Callback for Text nodes
                     *
                     * @var DOMText $node Text node
                     */
                    $this->_to_translate[$type][] = $node->data;
                },
                function (&$node, $type) {
                    /**
                     * Callback for Attribute nodes
                     *
                     * @var DOMAttr $node
                     */
                    $this->_to_translate[$type][] = $node->value;
                }
            );
        }

        foreach ($this->_to_translate as $type => $texts) {
            $this->_translations[$type] = $this->context->{$type}->translate($texts);
        }

        /*
         * Replace html node values to
         * Translated values
         * */
        foreach ($html_list as $html) {

            foreach ($languages as $language) {

                $this->getHtmlParser()->load($html);

                $this->getHtmlParser()->fetch(
                    function (&$node, $type) use ($language) {
                        /**
                         * Callback for Text node
                         *
                         * @var DOMText $node
                         * @var DOMElement $parent
                         */
                        $translate = isset(
                            $this->_translations[$type][$node->data][$language]
                        ) ? $this->_translations[$type][$node->data][$language]
                            : false;

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

                            if ($translate !== false) {
                                $text[] = [$node->data,
                                    htmlspecialchars($translate),
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
                         */

                        $translate = isset(
                            $this->_translations[$type][$node->value][$language]
                        ) ? $this->_translations[$type][$node->value][$language]
                        : false;

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
                            if ($translate !== false) {
                                $attr[$node->name] = [
                                    $node->value,
                                    htmlspecialchars($translate),
                                    $type
                                ];
                                $parent->setAttribute(
                                    $this->context->context->prefix . '-attr',
                                    json_encode($attr)
                                );
                            }
                        }

                        $node->value = $translate ?? $node->value;
                    }
                );

                $result[$html][$language] = $this->getHtmlParser()->save();
            }
        }

        return $result;
    }
}