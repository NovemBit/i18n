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

use DOMDocument;
use DOMElement;
use DOMXPath;
use Masterminds\HTML5;

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

    private $_type = interfaces\XML::XML;

    /**
     *
     * @var HTML5
     * */
    private $_html5;


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
        int $type = interfaces\XML::XML,
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
     * @return self
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
     * @return HTML5
     */
    public function _getHtml5(): HTML5
    {
        return $this->_html5;
    }

    /**
     * @param HTML5 $html5
     */
    public function _setHtml5(HTML5 $html5): void
    {
        $this->_html5 = $html5;
    }


    /**
     * Walk on DOMDocument and
     * run callback for each `DOMNode`
     *
     * @param callable $callback Callback function with two params
     *                           1. $node
     *                           2. $params
     *
     * @return void
     */
    public function fetch(callable $callback, array $data = []): void
    {
        $accept_queries = $this->_getQueryMap()['accept'] ?? [];

        $ignore_queries = $this->_getQueryMap()['ignore'] ?? [];


        $ignore_queries = !empty($ignore_queries)
            ? '[not(' . implode(' or ', $ignore_queries) . ')]' : '';


        foreach ($accept_queries as $query => $params) {

            $query .= $ignore_queries;

            $nodes = $this->getXpath()->query($query);

            /**
             * Fetching nodes to get each node in DomDocument
             *
             * @var DOMElement $node
             */
            foreach ($nodes as $node) {
                call_user_func_array($callback,[
                    $node,
                    $params,
                    $data
                ]);
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
    protected function setDom(DOMDocument $dom): void
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
        $this->setDom(new DomDocument('1.0', 'utf-8'));

        if ($this->_getType() === interfaces\XML::HTML) {

            $this->_setHtml5(
                new HTML5(
                    [
                        'encode_entities' => false,
                        'disable_html_ns' => true,
                    ]
                )
            );

            @$this->setDom($this->_getHtml5()->loadHTML($xml));

        } elseif ($this->_getType() === interfaces\XML::HTML_FRAGMENT) {
            $this->_setHtml5(
                new HTML5(
                    [
                        'encode_entities' => false,
                        'disable_html_ns' => true,
                    ]
                )
            );

            @$fragment = $this->_getHtml5()->loadHTMLFragment(
                $xml,
                [
                    'target_document' => $this->getDom()
                ]
            );

            $this->getDom()->appendChild($fragment);

        } else {
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

        if ($this->_getType() === interfaces\XML::HTML
            || $this->_getType() === interfaces\XML::HTML_FRAGMENT
        ) {
            $xml = $this->_getHtml5()->saveHTML($this->getDom());
        } else {
            $xml = $this->getDom()->saveXML();
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
    protected function getXpath(): DOMXPath
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
