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
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\Translator;
use NovemBit\i18n\models\exceptions\ActiveRecordException;


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
class XML extends Type
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'xml';

    /**
     * Xpath Query for parser
     *
     * @var string
     * */
    public $parser_query = ".//*[(text() or @*)]";

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

    public $xpath_query_map = [];

    /**
     * Show helper attributes that contains
     * All information about current node and child Text/Attr nodes
     *
     * @var bool
     * */
    private $_helper_attributes = false;

    public $save_translations = false;
    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\XML::class;

    private $_before_parse_callbacks = [];
    private $_after_parse_callbacks = [];

    protected $parser_type = \NovemBit\i18n\system\parsers\interfaces\XML::XML;

    /**
     * @return int
     */
    public function getParserType(): int
    {
        return $this->parser_type;
    }

    /**
     * @param int $parser_type
     */
    public function setParserType(int $parser_type): void
    {
        $this->parser_type = $parser_type;
    }

    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param string $xml XML content
     * @param string $language Language code
     *
     * @return \NovemBit\i18n\system\parsers\XML
     */
    protected function getParser(
        string $xml,
        string $language
    ): \NovemBit\i18n\system\parsers\XML {

        $parser = new \NovemBit\i18n\system\parsers\XML(
            $xml,
            $this->xpath_query_map,
            $this->getParserType(),
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
     * @param array $xml_list list of translatable HTML strings
     *
     * @return mixed
     * @throws ActiveRecordException
     *
     * @see DOMText
     * @see DOMAttr
     */
    protected function doTranslate(array $xml_list): array
    {
        $languages = $this->context->getLanguages();

        $result = [];

        $translations = [];

        $to_translate = [];

        $verbose = [];

        $_parsed_dom = [];

        $_parsed_dom_mutex = [];


        /**
         * Finding translatable node values and attributes
         * */
        foreach ($xml_list as $key => $html) {

            foreach ($languages as $language) {

                $_parsed_dom[$key][$language] = $this->getParser(
                    $html,
                    $language
                );

                $_parsed_dom[$key][$language]->fetch(
                    function (&$node, $params) use (&$to_translate) {
                        /**
                         * Define node type
                         *
                         * @var \DOMNode $node
                         */

                        $type = $params['type'] ?? 'text';
                        $node_value = null;
                        if ($node->nodeType == XML_TEXT_NODE
                            || $node->nodeType == XML_CDATA_SECTION_NODE
                        ) {

                            /**
                             * Define node type
                             *
                             * @var DOMText $node Text node
                             */

                            $node_value = $node->data;
                        } elseif ($node->nodeType == XML_ATTRIBUTE_NODE) {

                            /**
                             * Define node type
                             *
                             * @var DOMAttr $node Text node
                             */
                            $node_value = $node->value;

                        }
                        if ($node_value !== null) {
                            $to_translate[$type][] = $node_value;
                        }
                    }
                );
            }
        }

        foreach ($to_translate as $type => $texts) {
            $verbose[$type] = $this->getHelperAttributes() ? [] : null;

            /**
             * Translator method
             *
             * @var Translator $translator
             */
            $translator = $this->context->{$type};
            $translations[$type] = $translator->translate(
                $texts,
                $verbose[$type]
            );
        }


        /**
         * Replace html node values to
         * Translated values
         * */
        foreach ($xml_list as $key => $html) {

            foreach ($languages as $language) {

                $_parsed_dom[$key][$language]->fetch(
                    function (&$node, $params) use (
                        $translations,
                        $verbose,
                        $language,
                        &$_parsed_dom_mutex
                    ) {
                        /**
                         * Define type of $node
                         *
                         * @var \DOMNode $node
                         */

                        $node_path = $node->getNodePath();
                        if (isset($_parsed_dom_mutex[$node_path])) {
                            return;
                        }

                        $_parsed_dom_mutex[$node_path] = true;

                        $type = $params['type'] ?? 'text';

                        $node_value = null;

                        if ($node->nodeType == XML_TEXT_NODE
                            || $node->nodeType == XML_CDATA_SECTION_NODE
                        ) {
                            /**
                             * Define $node type
                             *
                             * @var DOMText $node Text node
                             */
                            $node_value = $node->data;
                            $node_type = 'text';
                        } elseif ($node->nodeType == XML_ATTRIBUTE_NODE) {
                            /**
                             * Define node type
                             *
                             * @var DOMAttr $node Text node
                             */
                            $node_value = $node->value;
                            $node_type = 'attr';
                        } else {
                            return;
                        }

                        $translate = $translations
                            [$type][$node_value][$language]
                            ?? null;

                        if ($this->getHelperAttributes()) {

                            /**
                             * Define node type
                             *
                             * @var DOMElement $parent
                             */
                            $parent = $node->parentNode;

                            $_verbose = $verbose
                                [$type][$node_value] ?? null;

                            if ($node_type == 'text') {
                                /**
                                 * Define node type
                                 *
                                 * @var DOMText $node
                                 */

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
                                        $node_value,
                                        $_verbose[$language]['translate'] ?? null,
                                        $type,
                                        $_verbose[$language]['level'] ?? null,
                                        $_verbose['prefix'] ?? null,
                                        $_verbose['suffix'] ?? null,
                                        $translate
                                    ];
                                    $parent->setAttribute(
                                        $this->context->context->prefix . '-text',
                                        json_encode($text)
                                    );
                                }
                            } elseif ($node_type == 'attr') {

                                /**
                                 * @var DOMAttr $node
                                 */

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
                                        $node_value,
                                        $_verbose[$language]['translate'] ?? null,
                                        $type,
                                        $_verbose[$language]['level'] ?? null,
                                        $_verbose['prefix'] ?? null,
                                        $_verbose['suffix'] ?? null,
                                        $translate
                                    ];
                                    $parent->setAttribute(
                                        $this->context->context->prefix . '-attr',
                                        json_encode($attr)
                                    );
                                }
                            }
                        }
                        if (!empty($translate)) {
                            if ($node_type == 'text') {
                                $node->data = $translate;
                            } elseif ($node_type == 'attr') {
                                $node->value = htmlspecialchars($translate);
                            }
                        }

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