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
use NovemBit\i18n\system\parsers\html\Rule;

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
class HTML
{

    /**
     * Html string content
     *
     * @var string
     * */
    private $_html;

    /**
     * Translate fields set
     *
     * @var array
     * */
    private $_translate_fields = [];


    /**
     * Main DomDocument
     *
     * @var DomDocument
     * */
    private $_dom;

    /**
     * Main xpath of dom
     *
     * @var DOMXPath
     * */
    private $_xpath;

    /**
     * Main query for Xpath
     *
     * @var string
     * */
    private $_query = './/*[ @* or text()]';

    /**
     * Keeping preserved data
     *
     * @var array
     * @see _preserveTag
     * @see _restorePreservedTag
     * */
    private $_preserved_data = [];

    /**
     * Special encoding fixer string
     *
     * @var string
     * */

    private $_xml_encoding_fixer = '<?xml encoding="utf-8"?>';
    /**
     * HTML constructor.
     *
     * @param string $html initial HTML content
     *
     * @return HTML
     */
    public function load(string $html) : self
    {
        $this->setHtml($html);

        $this->_initDom();

        return $this;
    }

    /**
     * Preserve tags
     * Actually using it for preserve script tags that
     * Impossible to parser with DOM parser
     *
     * @param string $html Referenced HTML content
     * @param string $tag  Tag that must be preserved
     * @param string $attr Attributes string
     *
     * @return void
     * @see    _preserveTag
     */
    private function _preserveTag(string &$html,
        string $tag,
        string $attr = ''
    ) : void {

        $atrr_pattern = !empty($attr) ? '('.$attr.')' : '';

        preg_match_all(
            /*(\stype="ld\+json")*/
            '/<' . $tag . '\b'.$atrr_pattern.'[^>]*>[\s\S]*?<\/' . $tag . '>/is',
            $html,
            $this->_preserved_data[$tag]
        );

        if (!empty($this->_preserved_data[$tag][0])) {
            $this->_preserved_data[$tag] = $this->_preserved_data[$tag][0];
        } else {
            $this->_preserved_data[$tag] = [];
        }

        $html = str_replace(
            $this->_preserved_data[$tag],
            "<__{$tag}__></__{$tag}__>",
            $html
        );
    }

    /**
     * Restoring preserved tags on HTML
     *
     * @param string $html Referenced HTML content
     * @param string $tag  Tag That should be restored
     *
     * @return void
     * @see    _preserveTag
     */
    private function _restorePreservedTag(string &$html, string $tag) : void
    {

        $first = 0;
        $search = "<__{$tag}__></__{$tag}__>";
        $tags = $this->_preserved_data[$tag] ?? [];
        for ($i = 0; $i < count($tags); $i++) {
            $replace = $tags[$i];
            $first = strpos(
                $html,
                $search,
                ($first == 0 ? 0 : $first + strlen($tags[$i - 1]))
            );

            if ($first === false) {
                continue;
            }
            $before = substr($html, 0, $first);
            $after = substr($html, $first + strlen($search));
            $html = $before . $replace . $after;
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
    public function fetch(callable $text_callback, callable $attr_callback) : void
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
                $rule = $translate_field['rule'];

                if (!$rule->validate($node)) {
                    continue;
                }


                if ($translate_field['text']) {
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
                                [&$child_node, $translate_field['text'], $rule]
                            );
                        }
                    }
                }

                /**
                 * Fetching current set attrs and checking
                 * If node has attribute with this keys
                 * */
                foreach ($translate_field['attrs'] as $attr => $type) {
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
    public function getDom() : DOMDocument
    {
        return $this->_dom;
    }

    /**
     * Set Dom (DomDocument)
     *
     * @param DomDocument $_dom Dom Document instance
     *
     * @return void
     */
    public function setDom(DomDocument $_dom) : void
    {
        $this->_dom = $_dom;
    }

    /**
     * Initialization DomDocument and Xpath
     * Preserving tag that must be restored on save
     *
     * @return void
     */
    private function _initDom() : void
    {
        $html = $this->getHtml();

        $this->_preserveTag(
            $html,
            'script',
            '(?!\stype="application\/ld\+json")+?'
        );

        $this->setDom(new DomDocument());

        /**
         * Set encoding of document UTF-8
         * */
        @$this->getDom()->loadHTML(
            $this->_xml_encoding_fixer.$html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $this->getDom()->encoding = 'utf-8';


        $this->_setXpath(new DOMXpath($this->_dom));

    }

    /**
     * Getting translate fields set
     *
     * @return Rule[]
     */
    public function getTranslateFields() : array
    {
        return $this->_translate_fields;
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
        $this->_translate_fields[] = [
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
    public function getHtml() : string
    {
        return $this->_html;
    }

    /**
     * Save DomDocument final result as HTML
     *
     * @return string
     */
    public function save() : string
    {
        $html = $this->getDom()->saveHTML();
        $this->_restorePreservedTag($html, 'script');

        /**
         * Remove <?xml.. syntax string
         * */
        $html = preg_replace(
            '/'.preg_quote($this->_xml_encoding_fixer).'/',
            '',
            $html,
            1
        );

        return $html;
    }

    /**
     * Set HTML string
     *
     * @param string $_html Initial HTML content
     *
     * @return void
     */
    public function setHtml(string $_html) : void
    {
        $this->_html = $_html;
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
    public function setQuery(string $_query) : void
    {
        $this->_query = $_query;
    }

    /**
     * Get Xpath instance
     *
     * @return DOMXpath
     */
    private function _getXpath() : DOMXPath
    {
        return $this->_xpath;
    }

    /**
     * Set Xpath instance
     *
     * @param DOMXPath $xpath Xpath instance of DomDocument
     *
     * @return void
     */
    private function _setXpath(DOMXpath $xpath) : void
    {
        $this->_xpath = $xpath;
    }
}
