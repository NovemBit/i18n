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
use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\translation\interfaces\Translator;
use NovemBit\i18n\component\translation\models\TranslationDataMapper;
use NovemBit\i18n\component\translation\Translation;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * HTML type for translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class XmlTranslator extends TypeAbstract implements interfaces\XML
{
    /**
     * {@inheritdoc}
     * */
    public string $name = 'xml';

    public array $xpath_query_map = [
        'ignore' => [
            'ancestor-or-self::*[@translate="no"]'
        ]
    ];

    /**
     * Save translations
     *
     * @var bool
     * */
    public bool $save_translations = false;

    /**
     * Model class name of ActiveRecord
     * */
    public string|\NovemBit\i18n\component\translation\models\TranslationDataMapper $model_class = models\XML::class;

    private array $before_parse_callbacks = [];

    private array $after_parse_callbacks = [];

    protected int $parser_type = \NovemBit\i18n\system\parsers\interfaces\XML::XML;

    public function __construct(
        private Localization $localization,
        Translation $translation,
        CacheInterface $cache,
        TranslationDataMapper $translation_data_mapper,
        private TypeTranslatorFactory $type_factory
    ) {
        parent::__construct($cache, $translation, $translation_data_mapper);
    }

    /**
     * @param  array|\string[][]  $xpath_query_map
     *
     * @return XmlTranslator
     */
    public function setXpathQueryMap(array $xpath_query_map): self
    {
        $this->xpath_query_map = $xpath_query_map;

        return $this;
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
     *
     * @noinspection PhpUnused
     */
    public function setParserType(int $parser_type): void
    {
        $this->parser_type = $parser_type;
    }

    /**
     * Get Html parser. Create new instance of HTML parser
     *
     * @param string $xml      XML content
     * @param string $to_language Language code
     *
     * @return \NovemBit\i18n\system\parsers\XML
     */
    protected function getParser(
        string $xml,
        string $from_language,
        string $to_language
    ): \NovemBit\i18n\system\parsers\XML
    {
        return (new \NovemBit\i18n\system\parsers\XML())
            ->setXml($xml)
            ->setQueryMap($this->xpath_query_map)
            ->setType($this->getParserType())
            ->setBeforeTranslateCallback(
                function ($xpath, $dom) {
                    foreach ($this->getBeforeParseCallbacks() as $callback) {
                        $callback($xpath, $dom);
                    }
                }
            )
            ->setAfterTranslateCallback(
                function ($xpath, $dom) use ($to_language) {
                    foreach ($this->getAfterParseCallbacks() as $callback) {
                        $callback($xpath, $dom);
                    }
                }
            )
            ->load();
    }

    /**
     * Get node value with node type
     *
     * @param  DOMNode  $node  Node element
     * @param  string  $type  type of node content
     * @param  string|null  $node_type
     *
     * @return string|null
     */
    private function getNodeValue(DOMNode $node, string $type, string &$node_type = null): ?string
    {
        $node_value = null;
        $node_type  = $this->getNodeType($node);

        if ($node_type === 'text') {
            /**
             * Define node type
             *
             * @var DOMText $node Text node
             */
            if ($type === 'url') {
                $node_value = urldecode($node->data);
            } else {
                $node_value = $node->data;
            }
        } elseif ($node_type === 'attr') {
            /**
             * Define node type
             *
             * @var DOMAttr $node Text node
             */
            if ($type === 'url') {
                $node_value = urldecode($node->value);
            } else {
                $node_value = $node->value;
            }
        }
        return $node_value;
    }

    private function getNodeType(DOMNode $node): ?string
    {
        if ($node->nodeType === XML_TEXT_NODE
            || $node->nodeType === XML_CDATA_SECTION_NODE) {
            return 'text';
        }

        if (
            $node->nodeType === XML_ATTRIBUTE_NODE
        ) {
            return 'attr';
        }

        return null;
    }

    public function buildToTranslateFields(
        DOMNode $node,
        array $params,
        array &$data
    ): void {
        $type = $params['type'] ?? 'text';

        $node_value = $this->getNodeValue($node, $type);

        if ($node_value !== null) {
            $data['to_translate'][$type][] = $node_value;
        }
    }

    /**
     * @param  DOMNode  $node
     * @param  array  $params
     * @param  array  $data
     *
     * @throws \JsonException
     */
    public function replaceTranslatedFields(
        DOMNode $node,
        array $params,
        array &$data
    ): void {
        /**
         * Define type of $node
         */
        $node_path = $node->getNodePath();
        if (isset($data['parsed_dom_mutex'][$node_path])) {
            return;
        }

        $data['parsed_dom_mutex'][$node_path] = true;

        $type = $params['type'] ?? 'text';

        $node_value = $this->getNodeValue($node, $type, $node_type);

        if ( ! $node_type) {
            return;
        }

        $translate = $data['translations']
            [$type][$node_value][$data['language']]
            ?? null;

        if (in_array($type, $this->getHelperAttributes(), true)) {
            /**
             * Define node type
             *
             * @var DOMElement $parent
             */
            $parent = $node->parentNode;

            $_verbose = $data['verbose']
                        [$type][$node_value] ?? null;

            if ($node_type === 'text') {
                /**
                 * Define node type
                 *
                 * @var DOMText $node
                 */
                if (
                $parent->hasAttribute(
                    $this->localization->getPrefix() . '-text'
                )
                ) {
                    $text = json_decode(
                        $parent->getAttribute(
                            $this->localization->getPrefix() . '-text'
                        ),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
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
                        $this->localization->getPrefix() . '-text',
                        json_encode($text, JSON_THROW_ON_ERROR)
                    );
                }
            } elseif ($node_type === 'attr') {
                /**
                 * Define node type
                 *
                 * @var DOMAttr $node
                 */
                if (
                $parent->hasAttribute(
                    $this->localization->getPrefix() . '-attr'
                )
                ) {
                    $attr = json_decode(
                        $parent->getAttribute(
                            $this->localization->getPrefix() . '-attr'
                        ),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
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
                        $this->localization->getPrefix() . '-attr',
                        json_encode($attr, JSON_THROW_ON_ERROR)
                    );
                }
            }
        }

        if (!empty($translate)) {
            if ($node_type === 'text') {
                $node->data = $translate;
            } elseif ($node_type === 'attr') {
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
     * @param  array  $nodes  list of translatable HTML strings
     * @param  string  $from_language  From Language
     * @param  array  $to_languages  To Languages
     * @param  bool  $ignore_cache  Ignore Cache
     *
     * @return mixed
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @see    DOMText
     * @see    DOMAttr
     */
    protected function doTranslate(
        array $nodes,
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
        foreach ($nodes as $key => $html) {
            foreach ($to_languages as $to_language) {
                $parsed_dom[$key][$to_language] = $this->getParser(
                    $html,
                    $from_language,
                    $to_language
                );

                $parsed_dom[$key][$to_language]->fetch(
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
            $translator = $this->type_factory->getTypeTranslator($type);

            /**
             * Enable helper attributes for sub-translators
             * */
            $translator->setHelperAttributes($this->getHelperAttributes());

            $translations[$type] = $translator->translate(
                $texts,
                $from_language,
                $to_languages,
                $verbose[$type],
                false,
                $ignore_cache
            );
        }


        /**
         * Replace html node values to
         * Translated values
         * */
        foreach ($nodes as $key => $html) {
            foreach ($to_languages as $language) {
                $parsed_dom[$key][$language]->fetch(
                    [$this, 'replaceTranslatedFields'],
                    [
                        'translations'     => $translations,
                        'verbose'          => $verbose,
                        'language'         => $language,
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

    public function getDbId(): int
    {
        return 5;
    }
}
