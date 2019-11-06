<?php
/**
 * HTML parser
 * php version 7.2.10
 *
 * @category System\Parsers
 * @package  System\Parsers
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\system\parsers;


use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use NovemBit\i18n\system\parsers\xml\Rule;

/**
 * HTML parser with callback function
 * Using PHP Dom parser
 *
 * @category System\Parsers
 * @package  System\Parsers
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class XML
{

    /**
     * Html string content
     *
     * @var string
     * */
    protected $xml;

    /**
     * Translate fields set
     *
     * @var array
     * */
    protected $translate_fields = [];

    /**
     * Main DomDocument
     *
     * @var DomDocument
     * */
    protected $dom;

    /**
     * Main xpath of dom
     *
     * @var DOMXPath
     * */
    protected $xpath;

    /**
     * Main query for Xpath
     *
     * @var string
     * */
    private $_query;

    /**
     * For script tags
     *
     * @var array[]
     * */
    private $_preserve_fields = [];

    /**
     * Keeping preserved data
     *
     * @var array
     * @see preserveField
     * @see restorePreservedTag
     * */
    private $_preserved_data = [];

    /**
     * Before translate callback
     *
     * @var callable
     * */
    private $_before_translate_callback = null;

    /**
     * After translate callback
     *
     * @var callable
     * */
    private $_after_translate_callback = null;

    /**
     * Special encoding fixer string
     *
     * @var string
     * */
    private $_xml_encoding_fixer = '<?xml encoding="utf-8"?>';

    private $_type = self::XML;

    const XML = 1;
    const HTML = 2;

    /**
     * HTML parser constructor.
     *
     * @param string        $xml                       XML content
     * @param string        $query                     Xpath Query
     * @param int           $type                      XML or HTML
     * @param callable|null $before_translate_callback Before init callback
     * @param callable|null $after_translate_callback  After init callback
     */
    public function __construct(
        string $xml,
        string $query,
        int $type = self::XML,
        callable $before_translate_callback = null,
        callable $after_translate_callback = null
    ) {
        $this->setQuery($query);
        $this->setType($type);
        $this->setBeforeTranslateCallback($before_translate_callback);
        $this->setAfterTranslateCallback($after_translate_callback);

        $this->load($xml);
    }


    /**
     * HTML constructor.
     *
     * @param string $xml initial HTML content
     *
     * @return XML
     */
    public function load(string $xml): self
    {
        $this->setXml($xml);

        $this->initDom();

        if ($this->getBeforeTranslateCallback() !== null) {
            call_user_func_array(
                $this->getBeforeTranslateCallback(),
                [&$this->xpath, &$this->dom]
            );
        }

        return $this;
    }

    /**
     * Preserve tags
     * Actually using it for preserve script tags that
     * Impossible to parser with DOM parser
     *
     * @param string $xml  Referenced HTML content
     * @param string $tag  Tag that must be preserved
     * @param string $attr Attributes pattern
     *
     * @return void
     * @see    preserveField
     */
    protected function preserveField(
        string &$xml,
        string $tag,
        string $attr = ''
    ): void {

        $new_tag = sprintf(
            '<__%s__ attr="%2$s"></__%1$s__>',
            $tag,
            md5($attr)
        );

        $attr = !empty($attr) ? '(' . $attr . ')' : '';

        $pattern = '/<' . $tag . '\b' . $attr . '[^>]*>[\s\S]*?<\/' . $tag . '>/is';

        $xml = preg_replace_callback(
            $pattern,
            function ($matches) use ($new_tag) {
                $this->_preserved_data[$new_tag][] = $matches[0];
                return $new_tag;
            },
            $xml
        );
    }

    /**
     * Restoring preserved tags on HTML
     *
     * @param string $xml  Referenced HTML content
     * @param string $tag  Tag That should be restored
     * @param string $attr Attributes pattern
     *
     * @return void
     * @see    preserveField
     */
    protected function restorePreservedTag(
        string &$xml,
        string $tag,
        string $attr
    ): void {

        $first = 0;
        $newtag = sprintf(
            '<__%s__ attr="%2$s"></__%1$s__>',
            $tag,
            md5($attr)
        );

        $tags = $this->_preserved_data[$newtag] ?? [];
        for ($i = 0; $i < count($tags); $i++) {
            $replace = $tags[$i];
            $first = strpos(
                $xml,
                $newtag,
                ($first == 0 ? 0 : $first + strlen($tags[$i - 1]))
            );

            if ($first === false) {
                continue;
            }
            $before = substr($xml, 0, $first);
            $after = substr($xml, $first + strlen($newtag));
            $xml = $before . $replace . $after;
        }
    }

    /**
     * Fetch current DOM document XPATH
     *
     * @param callable $text_callback Callback function for Text Nodes
     * @param callable $attr_callback Callback function for Attr Nodes
     *
     * @return void
     */
    public function fetch(callable $text_callback, callable $attr_callback): void
    {
        $nodes = $this->_getXpath()->query($this->getQuery());

        /**
         * Fetching nodes to get each node in DomDocument
         *
         * @var DOMElement $node
         */
        foreach ($nodes as $node) {
            foreach ($this->getTranslateFields() as $translate_field) {

                /**
                 * Getting Rule for current set of field
                 * Then validating node with this rule
                 *
                 * @var Rule $rule
                 */
                $rule = $translate_field['rule'] ?? null;

                if ($rule === null || !$rule->validate($node)) {
                    continue;
                }

                $text = $translate_field['text'] ?? null;

                if ($text != null) {
                    /**
                     * Fetching child nodes to find Text nodes
                     *
                     * @var DOMNode $child_node
                     */
                    foreach ($node->childNodes as $child_node) {
                        if ($child_node->nodeType == XML_TEXT_NODE
                            || $child_node->nodeType == XML_CDATA_SECTION_NODE
                        ) {
                            /**
                             * Checking if TextNode data length
                             * without whitespace in not null
                             * Then running callback function for text nodes
                             *
                             * @var DOMText $child_node
                             */
                            if (mb_strlen(trim($child_node->data)) == 0) {
                                continue;
                            }
                            call_user_func_array(
                                $text_callback,
                                [&$child_node, $text, $rule]
                            );
                        }
                    }
                }

                $attrs = $translate_field['attrs'] ?? [];

                /**
                 * Fetching current set attrs and checking
                 * If node has attribute with this keys
                 * */
                foreach ($attrs as $attr => $type) {
                    if ($node->hasAttribute($attr)) {
                        $attr_node = $node->getAttributeNode($attr);
                        /**
                         * Running callback function for attr nodes
                         *
                         * @var DOMAttr $node
                         */
                        call_user_func_array(
                            $attr_callback,
                            [&$attr_node, $type, $rule]
                        );
                    }
                }

                break;
            }
        }
    }

    /**
     * Get Dom (DomDocument)
     *
     * @return DOMDocument
     */
    protected function getDom(): DOMDocument
    {
        return $this->dom;
    }

    /**
     * Set Dom (DomDocument)
     *
     * @param DomDocument $dom Dom Document instance
     *
     * @return void
     */
    protected function setDom(DomDocument $dom): void
    {
        $this->dom = $dom;
    }

    /**
     * Initialization DomDocument and Xpath
     * Preserving tag that must be restored on save
     *
     * @return void
     */
    protected function initDom(): void
    {
        $xml = $this->getXml();

        if ($this->getType() === self::HTML) {
            $this->addPreserveField(
                'script',
                '(?!\stype="application\/ld\+json")+?'
            );
        }
        foreach ($this->getPreserveFields() as $field) {
            $this->preserveField(
                $xml,
                $field[0],
                $field[1]
            );
        }

        $this->setDom(new DomDocument());

        if ($this->getType() === self::HTML) {
            $this->getDom()->preserveWhiteSpace = false;
            $this->getDom()->formatOutput = true;
            /**
             * Set encoding of document UTF-8
             * */
            @$this->getDom()->loadHTML(
                $this->_xml_encoding_fixer . $xml,
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
            $this->getDom()->encoding = 'utf-8';

        } elseif ($this->getType() == self::XML) {

            @$this->getDom()->loadXML($xml);
        }

        $this->setXpath(new DOMXpath($this->getDom()));

    }

    /**
     * Getting translate fields set
     *
     * @return array[]
     */
    public function getTranslateFields(): array
    {
        return $this->translate_fields;
    }

    /**
     * Adding translate fields
     *
     * @param Rule   $rule  Rule object
     * @param string $text  Text node type to translate
     * @param array  $attrs List of attributes that must be translated
     *
     * @return void
     */
    public function addTranslateField(Rule $rule, string $text = 'text', $attrs = [])
    {
        $this->translate_fields[] = [
            'rule' => $rule,
            'text' => $text,
            'attrs' => $attrs
        ];
    }

    /**
     * Get HTML string
     *
     * @return string
     */
    public function getXml(): string
    {
        return $this->xml;
    }

    /**
     * Save DomDocument final result as HTML
     *
     * @return string
     */
    public function save(): string
    {
        if ($this->getAfterTranslateCallback() !== null) {
            call_user_func_array(
                $this->getAfterTranslateCallback(),
                [&$this->xpath, &$this->dom]
            );
        }

        if ($this->getType() === self::HTML) {
            $xml = $this->getDom()->saveHTML();
        } else {
            $xml = $this->getDom()->saveXML();
        }

        foreach ($this->getPreserveFields() as $field) {
            $this->restorePreservedTag($xml, $field[0], $field[1]);
        }

        if ($this->getType() === self::HTML) {
            /**
             * Remove <?xml.. syntax string
             * */
            $xml = preg_replace(
                '/' . preg_quote($this->_xml_encoding_fixer) . '/',
                '',
                $xml,
                1
            );
        }


        return $xml;
    }

    /**
     * Set HTML string
     *
     * @param string $xml Initial HTML content
     *
     * @return void
     */
    public function setXml(string $xml): void
    {
        $this->xml = $xml;
    }

    /**
     * Get Xpath query
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->_query;
    }

    /**
     * Set Xpath query
     *
     * @param string $_query Query String
     *
     * @return void
     */
    public function setQuery(string $_query): void
    {
        $this->_query = $_query;
    }

    /**
     * Get Xpath instance
     *
     * @return DOMXpath
     */
    private function _getXpath(): DOMXPath
    {
        return $this->xpath;
    }

    /**
     * Set Xpath instance
     *
     * @param DOMXPath $xpath Xpath instance of DomDocument
     *
     * @return void
     */
    protected function setXpath(DOMXpath $xpath): void
    {
        $this->xpath = $xpath;
    }

    /**
     * Adding preserved fields
     *
     * @param string|null $tag  Html tag
     * @param string|null $attr Html attribute
     *
     * @return void
     */
    public function addPreserveField(string $tag, ?string $attr): void
    {
        $this->_preserve_fields[] = [$tag, $attr];
    }

    /**
     * Get all preserve fields
     *
     * @return array[]
     */
    public function getPreserveFields(): array
    {
        return $this->_preserve_fields;
    }

    /**
     * Get before translate callback
     *
     * @return callable
     */
    public function getBeforeTranslateCallback(): ?callable
    {
        return $this->_before_translate_callback;
    }

    /**
     * Set before translate callback
     *
     * @param callable $before_translate_callback Callback
     *
     * @return void
     */
    public function setBeforeTranslateCallback(
        ?callable $before_translate_callback
    ): void {
        $this->_before_translate_callback = $before_translate_callback;
    }

    /**
     * Get after translate callback
     *
     * @return callable
     */
    public function getAfterTranslateCallback(): ?callable
    {
        return $this->_after_translate_callback;
    }

    /**
     * Set before translate callback
     *
     * @param callable $after_translate_callback Callback
     *
     * @return void
     */
    public function setAfterTranslateCallback(
        ?callable $after_translate_callback
    ): void {
        $this->_after_translate_callback = $after_translate_callback;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->_type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->_type = $type;
    }
}
