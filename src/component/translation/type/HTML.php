<?php

namespace NovemBit\i18n\component\translation\type;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use Exception;
use NovemBit\i18n\component\Translation;

/**
 * @property  Translation context
 */
class HTML extends Type
{

    public $type = 3;

    public $to_translate_xpath_query_expression = './/*[ @* or text()]';

    private $_to_translate_text = [];
    private $_to_translate_url = [];

    private $_text_translations = [];
    private $_url_translations = [];


    /*
     * Rules for DOMNode translation
     * */
    public $fields_to_translate
        = [
            ['tag' => 'title', 'translate_value' => 'text'],
            ['tag' => ['button'], 'translate_value' => 'text'],
            ['tag' => ['input'], 'attr' => ['type' => 'submit'], 'translate_attrs' => ['value' => 'text']],
            ['tag' => ['input'], 'attr' => ['placeholder' => '*'], 'translate_attrs' => ['placeholder' => 'text']],
            ['tag' => 'a', 'translate_attrs' => ['href' => 'url'], 'translate_value' => 'text'],
            [
                'tag'             => ['div', 'label', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'p'],
                'translate_attrs' => ['title' => 'text', 'alt' => 'text'],
                'translate_value' => 'text'
            ],
            [
                'tag'             => ['form'],
                'translate_attrs' => ['action' => 'url'],
                'translate_value' => 'text'
            ],
        ];


    public function init()
    {
        $this->prepareFieldsToTranslateRules();
    }

    private function prepareFieldsToTranslateRules()
    {
        foreach ($this->fields_to_translate as &$rule) {
            if (isset($rule['tag']) && ! empty($rule['tag'])) {
                if (is_string($rule['tag'])) {
                    $rule['tag'] = [$rule['tag']];
                }
            }

            if (isset($rule['attr']) && ! empty($rule['attr'])) {
                if (is_string($rule['attr'])) {
                    $rule['attr'] = [$rule['attr'] => ['*']];
                }

                foreach ($rule['attr'] as $attr => &$value) {
                    if (is_string($value)) {
                        $value = [$value];
                    }
                }
            }

            if (isset($rule['value']) && ! empty($rule['value'])) {
                if (is_string($rule['value'])) {
                    $rule['value'] = [$rule['value']];
                }
            }

            if ( ! isset($rule['translate_value'])) {
                $rule['translate_value'] = 'text';
            }

            if (isset($rule['translate_attrs']) && $rule['translate_attrs'] != false
                && ! empty($rule['translate_attrs'])
            ) {
                if (is_string($rule['translate_attrs'])) {
                    $rule['translate_attrs'] = [$rule['translate_attrs']];
                }
            }

        }
    }

    /**
     * @param DOMElement $node
     * @param callable   $text_callback
     * @param callable   $attribute_callback
     */
    private function fetchFields(&$node, callable $text_callback, callable $attribute_callback)
    {
        foreach ($this->fields_to_translate as $key => &$rule) {


            $fetch_count = $node->hasAttribute('fetch_count') ? (int)
            +1 : 1;

            $node->setAttribute('fetch_count', $fetch_count);

            if (isset($rule['tag']) && ! in_array($node->tagName, $rule['tag'])) {
                continue;
            }

            if (isset($rule['attr'])) {

                foreach ($rule['attr'] as $attribute => $values) {
                    if ( ! $node->hasAttribute($attribute)) {
                        continue 2;
                    } else {
                        if ( ! in_array('*', $values) && ! in_array($node->getAttribute($attribute), $values)) {
                            continue 2;
                        }
                    }
                }
            }

            if (isset($rule['value'])
                && ! in_array($node->nodeValue, $rule['value'])
            ) {
                continue;
            }

            if ($rule['translate_value']) {
                /** @var DOMNode $child_node */
                foreach ($node->childNodes as $child_node) {
                    if ($child_node->nodeType == XML_TEXT_NODE) {
                        /** @var DOMText $child_node */
                        if (strlen(trim($child_node->data)) == 0) {
                            continue;
                        }
                        call_user_func_array($text_callback, [&$child_node, $rule['translate_value']]);
                    }
                }
            }

            if (isset($rule['translate_attrs']) && ! empty($rule['translate_attrs'])) {
                foreach ($rule['translate_attrs'] as $attr => $type) {
                    if ($node->hasAttribute($attr)) {
                        $attr_node = $node->getAttributeNode($attr);
                        /** @var DOMAttr $node */
                        call_user_func_array($attribute_callback, [&$attr_node, $type]);
                    }
                }

            }
            break;
        }

    }


    /**
     * @param array $html_list
     *
     * @return mixed
     * @throws Exception
     */
    public function doTranslate(array $html_list)
    {
        $languages = $this->context->getLanguages();
        $result    = [];

        $this->_to_translate_text = [];
        $this->_to_translate_url  = [];
        $this->_text_translations = [];
        $this->_url_translations  = [];

        /*
         * Finding translatable node values and attributes
         * */
        foreach ($html_list as $html) {
            $dom = $this->getHtmlDom($html);

            $xpath = new DOMXpath($dom);

            $tags = $xpath->query($this->to_translate_xpath_query_expression);


            /** @var DOMElement $tag */
            foreach ($tags as $tag) {

                $this->fetchFields($tag,
                    /*
                     * Callback for Text nodes
                     * */
                    function (&$node, $type) {
                        /** @var DOMText $node */
                        if ($type == 'text') {
                            $this->_to_translate_text[] = $node->data;
                        } elseif ($type == 'url') {
                            $this->_to_translate_url[] = $node->data;
                        }
                    },
                    /*
                     * Callback for Attribute nodes
                     * */
                    function (&$node, $type) {
                        /** @var DOMAttr $node */
                        if ($type == 'text') {
                            $this->_to_translate_text[] = $node->value;
                        } elseif ($type == 'url') {
                            $this->_to_translate_url[] = $node->value;
                        }
                    });


            }
        }


        /*
         * Translate texts with method
         * */
        $this->_text_translations = $this->getTextTranslations($this->_to_translate_text);


        /*
         * Translate urls with method
         * */
        $this->_url_translations = $this->getUrlTranslations($this->_to_translate_url);

        /*
         * Replace html node values to
         * Translated values
         * */
        foreach ($html_list as $html) {

            foreach ($languages as $language) {

                $dom = $this->getHtmlDom($html);

                $xpath = new DOMXpath($dom);

                $tags = $xpath->query($this->to_translate_xpath_query_expression);

                /** @var DOMElement $tag */
                foreach ($tags as $tag) {
                    $this->fetchFields($tag,
                        /*
                         * Callback for Text nodes
                         * */
                        function (&$node, $type) use ($language) {
                            /** @var DOMText $node */

                            if ($this->_text_translations[$node->data][$language] != null) {
                                if ($type == 'text') {
                                    if ($this->_text_translations[$node->data][$language] != null) {
                                        $node->data = $this->_text_translations[$node->data][$language];
                                    }
                                } elseif ($type == 'url') {
                                    if ($this->_url_translations[$node->data][$language] != null) {
                                        $node->data
                                            = htmlspecialchars($this->_url_translations[$node->data][$language]);
                                    }
                                }
                            }
                        },
                        /*
                         * Callback for Attribute nodes
                         * */
                        function (&$node, $type) use ($language) {
                            /** @var DOMAttr $node */
                            if ($type == 'text') {
                                if ($this->_text_translations[$node->value][$language] != null) {
                                    $node->value = $this->_text_translations[$node->value][$language];
                                }
                            } elseif ($type == 'url') {
                                if ($this->_url_translations[$node->value][$language] != null) {
                                    $node->value
                                        = htmlspecialchars($this->_url_translations[$node->value][$language]);
                                }
                            }
                        });

                }


                $result[$html][$language] = $this->getDomHtmlString($dom);
            }
        }


        return $result;

    }

    /**
     * @param DOMDocument $dom
     *
     * @return string|string[]|null
     */
    private function getDomHtmlString($dom)
    {
        return preg_replace('/(^\<root>|\<\/root>$)/', '', $dom->saveHTML());
    }

    /**
     * @param $html
     *
     * @return DOMDocument
     */
    private function getHtmlDom($html)
    {

        if ( ! preg_match('/\<html.*?\>/i', $html)) {
            $html = '<root>' . $html . '</root>';
        }
        $dom = new DomDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return $dom;
    }

    /**
     * @param array $strings
     *
     * @return mixed
     * @throws Exception
     */
    private function getTextTranslations(array $strings)
    {

        $translate = $this->context->text->translate($strings);

        return $translate;
    }

    /**
     * @param array $urls
     *
     * @return mixed
     * @throws Exception
     */
    private function getUrlTranslations(array $urls)
    {

        $translate = $this->context->url->translate($urls);

        return $translate;
    }
}