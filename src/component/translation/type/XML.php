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

use Doctrine\DBAL\ConnectionException;
use DOMAttr;
use DOMElement;
use DOMNode;
use DOMText;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\interfaces\Translator;
use Psr\SimpleCache\InvalidArgumentException;

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
class XML extends Type implements interfaces\XML
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'xml';

    public $xpath_query_map = [];

    /**
     * Save translations
     *
     * @var bool
     * */
    public $save_translations = false;

    /**
     * @var bool
     */
    public $get_translations_from_db = false;

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\XML::class;

    private $before_parse_callbacks = [];

    private $after_parse_callbacks = [];

    protected $parser_type = \NovemBit\i18n\system\parsers\interfaces\XML::XML;

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [
            'xpath_query_map' => [
                'ignore' => [
                    'ancestor-or-self::*[@translate="no"]'
                ]
            ]
        ];
    }

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
     * @param string $xml      XML content
     * @param string $language Language code
     *
     * @return \NovemBit\i18n\system\parsers\XML
     */
    protected function getParser(
        string $xml,
        string $language
    ): \NovemBit\i18n\system\parsers\XML {
        return new \NovemBit\i18n\system\parsers\XML(
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
    }

    /**
     * Get node value with node type
     *
     * @param DOMNode $node Node element
     * @param string  $type type of node content
     *
     * @return string|null
     */
    private function _getNodeValue($node, $type, &$node_type = null): ?string
    {
        $node_value = null;
        $node_type = $this->_getNodeType($node);

        if ($node_type == 'text') {
            /**
             * Define node type
             *
             * @var DOMText $node Text node
             */
            if ($type == 'url') {
                $node_value = urldecode($node->data);
            } else {
                $node_value = $node->data;
            }
        } elseif ($node_type == 'attr') {
            /**
             * Define node type
             *
             * @var DOMAttr $node Text node
             */
            if ($type == 'url') {
                $node_value = urldecode($node->value);
            } else {
                $node_value = $node->value;
            }
        }
        return $node_value;
    }

    private function _getNodeType($node): ?string
    {
        if (
            $node->nodeType == XML_TEXT_NODE
            || $node->nodeType == XML_CDATA_SECTION_NODE
        ) {
            return 'text';
        } elseif ($node->nodeType == XML_ATTRIBUTE_NODE) {
            return 'attr';
        } else {
            return null;
        }
    }

    public function buildToTranslateFields(
        DOMNode &$node,
        array $params,
        array &$data
    ) {
        /**
         * Define node type
         *
         * @var DOMNode $node
         */
        $type = $params['type'] ?? 'text';

        $node_value = $this->_getNodeValue($node, $type);

        if ($node_value !== null) {
            $data['to_translate'][$type][] = $node_value;
        }
    }

    public function replaceTranslatedFields(
        DOMNode &$node,
        array $params,
        array &$data
    ) {
        /**
         * Define type of $node
         *
         * @var DOMNode $node
         */

        $node_path = $node->getNodePath();
        if (isset($data['parsed_dom_mutex'][$node_path])) {
            return;
        }

        $data['parsed_dom_mutex'][$node_path] = true;

        $type = $params['type'] ?? 'text';

        $node_value = $this->_getNodeValue($node, $type, $node_type);

        if ($node_type == null) {
            return;
        }

        $translate = $data['translations']
            [$type][$node_value][$data['language']]
            ?? null;

        if (in_array($type, $this->getHelperAttributes())) {
            /**
             * Define node type
             *
             * @var DOMElement $parent
             */
            $parent = $node->parentNode;

            $_verbose = $data['verbose']
                [$type][$node_value] ?? null;

            if ($node_type == 'text') {
                /**
                 * Define node type
                 *
                 * @var DOMText $node
                 */
                if (
                    $parent->hasAttribute(
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
                        $_verbose['after'] ?? null,
                        $_verbose[$data['language']]['translate'] ?? null,
                        $type,
                        $_verbose[$data['language']]['level'] ?? null,
                        $_verbose['prefix'] ?? null,
                        $_verbose['suffix'] ?? null
                    ];
                    $parent->setAttribute(
                        $this->context->context->prefix . '-text',
                        json_encode($text)
                    );
                }
            } elseif ($node_type == 'attr') {
                /**
                 * Define node type
                 *
                 * @var DOMAttr $node
                 */
                if (
                    $parent->hasAttribute(
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
                        $_verbose['after'] ?? null,
                        $_verbose[$data['language']]['translate'] ?? null,
                        $type,
                        $_verbose[$data['language']]['level'] ?? null,
                        $_verbose['prefix'] ?? null,
                        $_verbose['suffix'] ?? null
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
     * @param array  $xml_list      list of translatable HTML strings
     * @param string $from_language From Language
     * @param array  $to_languages  To Languages
     * @param bool   $ignore_cache  Ignore Cache
     *
     * @return mixed
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @see    DOMText
     * @see    DOMAttr
     */
    protected function doTranslate(
        array $xml_list,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        $result = [];

        $translations = [];

        $to_translate = [];

        $verbose = [];

        $parsed_dom = [];

        $parsed_dom_mutex = [];

        /**
         * Finding translatable node values and attributes
         * */
        foreach ($xml_list as $key => $html) {
            foreach ($to_languages as $language) {
                $parsed_dom[$key][$language] = $this->getParser(
                    $html,
                    $language
                );

                $parsed_dom[$key][$language]->fetch(
                    [$this, 'buildToTranslateFields'],
                    ['to_translate' => &$to_translate]
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

            /**
             * Enable helper attributes for sub-translators
             * */
            $translator->setHelperAttributes($this->getHelperAttributes());

            $translations[$type] = $translator->translate(
                $texts,
                $verbose[$type],
                false,
                $ignore_cache
            );
        }


        /**
         * Replace html node values to
         * Translated values
         * */
        foreach ($xml_list as $key => $html) {
            foreach ($to_languages as $language) {
                $parsed_dom[$key][$language]->fetch(
                    [$this, 'replaceTranslatedFields'],
                    [
                        'translations' => $translations,
                        'verbose' => $verbose,
                        'language' => $language,
                        'parsed_dom_mutex' => &$parsed_dom_mutex
                    ]
                );

                $result[$html][$language] = $parsed_dom[$key][$language]->save();
            }
        }

        return $result;
    }

    /**
     * Adding functions before parse
     *
     * @param callable $callback Callable closure
     */
    public function addBeforeParseCallback(callable $callback): void
    {
        $this->before_parse_callbacks[] = $callback;
    }

    /**
     * @param callable $callback
     */
    public function addAfterParseCallback(callable $callback): void
    {
        $this->after_parse_callbacks[] = $callback;
    }

    /**
     * @return array
     */
    public function getBeforeParseCallbacks(): array
    {
        return $this->before_parse_callbacks;
    }

    /**
     * @return array
     */
    public function getAfterParseCallbacks(): array
    {
        return $this->after_parse_callbacks;
    }

    public function addXpathQuery(string $xpath, array $config): void
    {
        $this->xpath_query_map[$xpath] = $config;
    }
}
