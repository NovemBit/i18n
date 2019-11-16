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
class XML2
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
    protected $query_map = [];

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
     * @param string $xml XML content
     * @param array $query_map Xpath Query
     * @param int $type XML or HTML
     * @param callable|null $before_translate_callback Before init callback
     * @param callable|null $after_translate_callback After init callback
     */
    public function __construct(
        string $xml,
        array $query_map,
        int $type = self::XML,
        callable $before_translate_callback = null,
        callable $after_translate_callback = null
    ) {

        $this->_setType($type);
        $this->_setQueryMap($query_map);
        $this->_setBeforeTranslateCallback($before_translate_callback);
        $this->_setAfterTranslateCallback($after_translate_callback);

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
        $this->_setXml($xml);

        $this->initDom();

        if ($this->_getBeforeTranslateCallback() !== null) {
            call_user_func_array(
                $this->_getBeforeTranslateCallback(),
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
     * @param string $xml Referenced HTML content
     * @param string $tag Tag that must be preserved
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
     * @param string $xml Referenced HTML content
     * @param string $tag Tag That should be restored
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

    public function fetch(callable $callback): void
    {
        $accept_queries = $this->_getQueryMap()['accept'] ?? [];

        $ignore_queries = $this->_getQueryMap()['ignore'] ?? [];

        $ignore_queries = !empty($ignore_queries)
            ? '[not('.implode(' or ',$ignore_queries).')]' : '';


        foreach ($accept_queries as $query => $params) {

            $query .= $ignore_queries;

            $nodes = $this->_getXpath()->query($query);
            /**
             * Fetching nodes to get each node in DomDocument
             *
             * @var DOMElement $node
             */
            foreach ($nodes as $node) {

                call_user_func_array($callback, [&$node, $params]);
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
        $xml = $this->_getXml();

        if ($this->_getType() === self::HTML) {
            $this->_addPreserveField(
                'script',
                '(?!\stype="application\/ld\+json")+?'
            );
        }
        foreach ($this->_getPreserveFields() as $field) {
            $this->preserveField(
                $xml,
                $field[0],
                $field[1]
            );
        }

        $this->setDom(new DomDocument());

        if ($this->_getType() === self::HTML) {
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

        } elseif ($this->_getType() == self::XML) {

            @$this->getDom()->loadXML($xml);
        }

        $this->setXpath(new DOMXpath($this->getDom()));

    }

    /**
     * Get HTML string
     *
     * @return string
     */
    private function _getXml(): string
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
        if ($this->_getAfterTranslateCallback() !== null) {
            call_user_func_array(
                $this->_getAfterTranslateCallback(),
                [&$this->xpath, &$this->dom]
            );
        }

        if ($this->_getType() === self::HTML) {
            $xml = $this->getDom()->saveHTML();
        } else {
            $xml = $this->getDom()->saveXML();
        }

        foreach ($this->_getPreserveFields() as $field) {
            $this->restorePreservedTag($xml, $field[0], $field[1]);
        }

        if ($this->_getType() === self::HTML) {
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
    private function _setXml(string $xml): void
    {
        $this->xml = $xml;
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
     * @param string|null $tag Html tag
     * @param string|null $attr Html attribute
     *
     * @return void
     */
    private function _addPreserveField(string $tag, ?string $attr): void
    {
        $this->_preserve_fields[] = [$tag, $attr];
    }

    /**
     * Get all preserve fields
     *
     * @return array[]
     */
    private function _getPreserveFields(): array
    {
        return $this->_preserve_fields;
    }

    /**
     * Get before translate callback
     *
     * @return callable
     */
    private function _getBeforeTranslateCallback(): ?callable
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
    private function _setBeforeTranslateCallback(
        ?callable $before_translate_callback
    ): void {
        $this->_before_translate_callback = $before_translate_callback;
    }

    /**
     * Get after translate callback
     *
     * @return callable
     */
    private function _getAfterTranslateCallback(): ?callable
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
    private function _setAfterTranslateCallback(
        ?callable $after_translate_callback
    ): void {
        $this->_after_translate_callback = $after_translate_callback;
    }

    /**
     * @return int
     */
    private function _getType(): int
    {
        return $this->_type;
    }

    /**
     * @param int $type
     */
    private function _setType(int $type): void
    {
        $this->_type = $type;
    }

    /**
     * @return array
     */
    private function _getQueryMap(): array
    {
        return $this->query_map;
    }

    /**
     * @param array $query_map
     */
    private function _setQueryMap(array $query_map): void
    {
        $this->query_map = $query_map;
    }
}
