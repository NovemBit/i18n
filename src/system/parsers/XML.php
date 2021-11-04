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

use Closure;
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
    protected string $xml;

    /**
     * Translate fields set
     *
     * @var array
     * */
    protected array $query_map = [];

    /**
     * Main DomDocument
     *
     * @var DomDocument
     * */
    protected DomDocument $dom;

    /**
     * Main xpath of dom
     *
     * @var DOMXPath
     * */
    protected DOMXPath $xpath;

    /**
     * Before translate callback
     * */
    private Closure $before_translate_callback;

    /**
     * After translate callback
     *
     * @var Closure
     * */
    private Closure $after_translate_callback;

    private int $type = interfaces\XML::XML;

    private HTML5 $html5;

    /**
     * HTML constructor.
     *
     * @return self
     */
    public function load(): self
    {
        $this->setXml($this->xml);

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
        return $this->html5;
    }

    public function setHtml5(HTML5 $html5): self
    {
        $this->html5 = $html5;

        return $this;
    }


    /**
     * Walk on DOMDocument and
     * run callback for each `DOMNode`
     *
     * @param  callable  $callback  Callback function with two params
     *                           1. $node
     *                           2. $params
     * @param  array  $data
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
                call_user_func_array($callback, [
                    &$node,
                    $params,
                    &$data
                ]);
            }
        }
    }

    protected function getDom(): DOMDocument
    {
        return $this->dom;
    }

    /**
     * @param  DomDocument  $dom
     *
     * @return XML
     */
    public function setDom(DOMDocument $dom): self
    {
        $this->dom = $dom;

        return $this;
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
            $this->setHtml5(
                new HTML5(
                    [
                        'encode_entities' => false,
                        'disable_html_ns' => true,
                    ]
                )
            );

            @$this->setDom($this->_getHtml5()->loadHTML($xml));

        } elseif ($this->_getType() === interfaces\XML::HTML_FRAGMENT) {
            $this->setHtml5(
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
        ) {
            $xml = $this->_getHtml5()->saveHTML($this->getDom());
        } elseif ($this->_getType() === interfaces\XML::HTML_FRAGMENT) {
            $xml = $this->_getHtml5()->saveHTML($this->getDom());
            $xml = preg_replace('/(?>^<!DOCTYPE.+?>(?>\\n)?)|(?>\\n$)/i', '', $xml);
        } else {
            $xml = $this->getDom()->saveXML();
        }

        return $xml;
    }

    /**
     * Set HTML string
     *
     * @param string $xml Initial HTML content
     */
    public function setXml(string $xml): self
    {
        $this->xml = $xml;

        return $this;
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
    protected function setXpath(DOMXpath $xpath): self
    {
        $this->xpath = $xpath;

        return $this;
    }

    /**
     * Get before translate callback
     *
     * @return callable
     */
    private function _getBeforeTranslateCallback(): ?callable
    {
        return $this->before_translate_callback;
    }

    public function setBeforeTranslateCallback(
        Closure $before_translate_callback
    ): self {
        $this->before_translate_callback = $before_translate_callback;

        return $this;
    }

    /**
     * Get after translate callback
     *
     * @return callable
     */
    private function _getAfterTranslateCallback(): ?callable
    {
        return $this->after_translate_callback;
    }

    /**
     * Set before translate callback
     */
    public function setAfterTranslateCallback(
        Closure $after_translate_callback
    ): self {
        $this->after_translate_callback = $after_translate_callback;

        return $this;
    }

    /**
     * @return int
     */
    private function _getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    private function _getQueryMap(): array
    {
        return $this->query_map;
    }

    public function setQueryMap(array $query_map): self
    {
        $this->query_map = $query_map;

        return $this;
    }
}
