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
class HTML extends Type implements interfaces\HTML
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
    public $parser_query;

    /**
     * Fields to translate
     *
     * ```php
     * [
     *  ...
     *  [
     *   'rule' => ['tags' => ['a']],
     *   'attrs' => [
     *      'title' => Text::NAME,
     *      'alt' => Text::NAME,
     *      'href' => URL::NAME,
     *      'data-tooltip' => Text::NAME,
     *      'data-tip' => Text::NAME
     *    ],
     *    'text' => Text::NAME
     *   ]
     *  ...
     * ]
     * ```
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
    private $_helper_attributes = false;

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
     * Translated contents
     *
     * @var array
     * */
    private $_verbose = [];

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\HTML::class;

    private $_before_parse_callbacks = [];
    private $_after_parse_callbacks = [];


    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param string $html Html content
     *
     * @return \NovemBit\i18n\system\parsers\HTML
     */
    private function _getHtmlParser(
        string $html,
        string $language
    ): \NovemBit\i18n\system\parsers\HTML {

        $parser = new \NovemBit\i18n\system\parsers\HTML(
            $html,
            $this->parser_query,
            function ($xpath, $dom) {
                foreach ($this->getBeforeParseCallbacks() as $callback) {
                    call_user_func_array($callback, [$xpath, $dom]);
                }
            },
            function ($xpath, $dom) use ($language) {
                foreach ($this->getAfterParseCallbacks() as $callback) {
                    call_user_func_array($callback, [$xpath, $dom]);
                }
            }
        );

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
     * @return mixed
     * @throws ActiveRecordException
     *
     * @see DOMText
     * @see DOMAttr
     */
    public function doTranslate(array $html_list): array
    {
        $languages = $this->context->getLanguages();

        $result = [];

        $this->_translations = [];

        $_parsed_dom = [];

        $this->_verbose = [];

        /*
         * Finding translatable node values and attributes
         * */
        foreach ($html_list as $key => $html) {

            foreach ($languages as $language) {

                $_parsed_dom[$key][$language] = $this->_getHtmlParser(
                    $html,
                    $language
                );

                $_parsed_dom[$key][$language]->fetch(
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
        }

        foreach ($this->_to_translate as $type => $texts) {
            $this->_verbose[$type] = $this->getHelperAttributes() ? [] : null;

            /**
             * Translator method
             *
             * @var Translator $translator
             */
            $translator = $this->context->{$type};
            $this->_translations[$type] = $translator->translate(
                $texts,
                $this->_verbose[$type]
            );
        }

        /**
         * Replace html node values to
         * Translated values
         * */
        foreach ($html_list as $key => $html) {

            foreach ($languages as $language) {

                $_parsed_dom[$key][$language]->fetch(
                    function (&$node, $type, $rule) use ($language) {
                        /**
                         * Callback for Text node
                         *
                         * @var DOMText $node
                         * @var DOMElement $parent
                         * @var Rule $rule
                         */
                        $translate = $this->_translations
                            [$type][$node->data][$language]
                            ?? null;

                        /**
                         * Enable helper attributes
                         * */
                        if ($this->getHelperAttributes()) {

                            $parent = $node->parentNode;

                            $verbose = $this->_verbose
                                [$type][$node->data] ?? null;


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
                                    $verbose[$language]['translate'] ?? null,
                                    $type,
                                    $verbose[$language]['level'] ?? null,
                                    $verbose['prefix'] ?? null,
                                    $verbose['suffix'] ?? null
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
                        if ($this->getHelperAttributes()) {

                            $parent = $node->parentNode;

                            $verbose = $this->_verbose
                                [$type][$node->value] ?? null;

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
                                    $verbose[$language]['translate'] ?? null,
                                    $type,
                                    $verbose[$language]['level'] ?? null,
                                    $verbose['prefix'] ?? null,
                                    $verbose['suffix'] ?? null
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

                $result[$html][$language] = $_parsed_dom[$key][$language]->save();
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     * */
    public function getHelperAttributes(): bool
    {
        return $this->_helper_attributes;
    }

    /**
     * {@inheritDoc}
     *
     * @param bool $status If true then
     *                     html translation including additional attributes
     *
     * @return void
     * */
    public function setHelperAttributes(bool $status): void
    {
        $this->_helper_attributes = $status;
    }

    public function addBeforeParseCallback(callable $callback): void
    {
        $this->_before_parse_callbacks[] = $callback;
    }

    public function addAfterParseCallback(callable $callback): void
    {
        $this->_after_parse_callbacks[] = $callback;
    }

    /**
     * @return array
     */
    public function getBeforeParseCallbacks(): array
    {
        return $this->_before_parse_callbacks;
    }

    /**
     * @return array
     */
    public function getAfterParseCallbacks(): array
    {
        return $this->_after_parse_callbacks;
    }
}