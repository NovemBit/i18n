<?php


namespace NovemBit\i18n\system\parsers;


use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\parsers\html\Rule;

class HTML
{
    private $html;

    private $translate_fields = [];

    private $dom;

    private $xpath;

    private $query = './/*[ @* or text()]';

    private $preserved_data = [];

    /**
     * HTML constructor.
     *
     * @param $html
     *
     * @return HTML
     */
    public function load($html)
    {
        $this->setHtml($html);

        $this->initDom();

        return $this;
    }

    private function preserveTag(&$html, $tag)
    {
        preg_match_all('/<' . $tag . '\b[^>]*>[\s\S]*?<\/' . $tag . '>/is', $html, $this->preserved_data[$tag]);

        if (!empty($this->preserved_data[$tag][0])) {
            $this->preserved_data[$tag] = $this->preserved_data[$tag][0];
        } else {
            $this->preserved_data[$tag] = [];
        }

        $html = str_replace($this->preserved_data[$tag], '<script></script>', $html);
    }

    private function restorePreservedTag(&$html, $tag)
    {
        $first = 0;
        $search = "<$tag></$tag>";
        $tags = $this->preserved_data[$tag] ?? [];
        for ($i = 0; $i < count($tags); $i++) {
            $replace = $tags[$i];
            $first = strpos($html, $search, ($first == 0 ? 0 : $first + strlen($tags[$i - 1])));
            if ($first === false) {
                continue;
            }
            $before = substr($html, 0, $first);
            $after = substr($html, $first + strlen($search));
            $html = $before . $replace . $after;
        }
    }

    /**
     * @param $text_callback
     * @param $attr_callback
     */
    public function fetch(callable $text_callback, callable $attr_callback)
    {
        $nodes = $this->getXpath()->query($this->getQuery());

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            foreach ($this->getTranslateFields() as $translate_field) {


                /** @var Rule $rule */
                $rule = $translate_field['rule'];

                if (!$rule->validate($node)) {
                    continue;
                }


                if ($translate_field['text']) {
                    /** @var DOMNode $child_node */
                    foreach ($node->childNodes as $child_node) {
                        if ($child_node->nodeType == XML_TEXT_NODE) {
                            /** @var DOMText $child_node */
                            if (strlen(trim($child_node->data)) == 0) {
                                continue;
                            }
                            call_user_func_array($text_callback, [&$child_node, $translate_field['text']]);
                        }
                    }
                }

                foreach ($translate_field['attrs'] as $attr => $type) {
                    if ($node->hasAttribute($attr)) {
                        $attr_node = $node->getAttributeNode($attr);
                        /** @var DOMAttr $node */
                        call_user_func_array($attr_callback, [&$attr_node, $type]);
                    }
                }

                break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param DomDocument $dom
     */
    public function setDom(DomDocument $dom)
    {
        $this->dom = $dom;
    }

    /**
     * @return void
     */
    private function initDom()
    {
        $html = $this->getHtml();

        $this->preserveTag($html, 'script');

        $this->setDom(new DomDocument());
        @$this->getDom()->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $this->setXpath(new DOMXpath($this->dom));

    }

    /**
     * @return Rule[]
     */
    public function getTranslateFields()
    {
        return $this->translate_fields;
    }

    /**
     * @param Rule $rule
     * @param array $attrs
     * @param string $text
     */
    public function addTranslateField(Rule $rule, $text = 'text', $attrs = [])
    {
        $this->translate_fields[] = ['rule' => $rule, 'text' => $text, 'attrs' => $attrs];
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string|string[]|null
     */
    public function save()
    {
        $html = $this->getDom()->saveHTML();
        $this->restorePreservedTag($html, 'script');
        return $html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * @return DOMXpath
     */
    private function getXpath()
    {
        return $this->xpath;
    }

    /**
     * @param DOMXPath $xpath
     */
    private function setXpath(DOMXpath $xpath)
    {
        $this->xpath = $xpath;
    }
}