<?php

namespace NovemBit\i18n\component\translation\type;

use DOMAttr;
use DOMDocument;
use DOMElement;
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

//clean    public $to_translate_xpath_query_expression = './/*[not(child::*) and (not(self::html) and not(self::body) and not(self::style) and not(self::script) and not(self::body)) and text()[normalize-space()]]';
//    public $to_translate_xpath_query_expression = './/*[not(child::*[not(text()[normalize-space()])]) and (not(self::html) and not(self::body) and not(self::style) and not(self::script) and not(self::body)) and text()[normalize-space()]]';
//    public $to_translate_xpath_query_expression = './/*[not(child::*)]';
    public $to_translate_xpath_query_expression = './/text()[normalize-space()] | .//@*[not(self::fetch_count)]';

    private $_to_translate_text = [];
    private $_to_translate_url = [];

    private $_text_translations = [];
    private $_url_translations = [];


    public $fields_to_translate
        = [
            ['tag' => 'title', 'translate_value' => true],
            ['tag' => ['button'], 'translate_value' => true],
            ['tag' => ['input'], 'attr' => ['type' => 'submit'], 'translate_attrs' => ['value' => 'text']],
            ['tag' => ['input'], 'attr' => ['placeholder' => '*'], 'translate_attrs' => ['placeholder' => 'text']],
            ['tag' => 'a', 'translate_attrs' => ['href' => 'url'], 'translate_value' => true],
            [
                'tag'             => ['div', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'p'],
                'translate_attrs' => ['title' => 'text', 'alt' => 'text'],
                'translate_value' => true
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

            if (isset($rule['translate_attrs']) && $rule['translate_attrs'] != false
                && ! empty($rule['translate_attrs'])
            ) {
                if (is_string($rule['translate_attrs'])) {
                    $rule['translate_attrs'] = [$rule['translate_attrs']];
                }
            }

        }
    }

    private $_node_mutex = [];

    /**
     * @param DOMElement $node
     * @param            $callback
     *
     */
    private function fetchFields(&$node, $callback)
    {

        foreach ($this->fields_to_translate as $key => &$rule) {

            $elementNode = $node;

            if ($node->nodeType != XML_ELEMENT_NODE) {
                $elementNode = $node->parentNode;
            }


            $fetch_count = $elementNode->hasAttribute('fetch_count') ? (int)
                                                                       + 1 : 1;

            $elementNode->setAttribute('fetch_count', $fetch_count);

            if (isset($rule['tag']) && ! in_array($elementNode->tagName, $rule['tag'])) {
                continue;
            }

            if (isset($rule['attr'])) {

                foreach ($rule['attr'] as $attribute => $values) {
                    if ( ! $elementNode->hasAttribute($attribute)) {
                        continue 2;
                    } else {
                        if ( ! in_array('*', $values) && ! in_array($elementNode->getAttribute($attribute), $values)) {
                            continue 2;
                        }
                    }
                }
            }

            if ($node->nodeType == XML_TEXT_NODE && isset($rule['value'])
                && ! in_array($node->nodeValue, $rule['value'])
            ) {
                continue;
            }


            call_user_func_array($callback, [&$node, &$rule]);

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

            $hashes = [];

            /** @var DOMElement $tag */
            foreach ($tags as $tag) {


                $this->fetchFields($tag, function (&$node, &$rule) use ($hashes) {
                    /** @var DOMElement $node */

                    if ($node->nodeType == XML_TEXT_NODE && isset($rule['translate_value'])
                        && $rule['translate_value']
                    ) {
                        /** @var DOMText $node */
                        $this->_to_translate_text[] = $node->data;
                    }

                    if ($node->nodeType == XML_ATTRIBUTE_NODE && isset($rule['translate_attrs'])) {
                        /** @var DOMAttr $node */
                        if (isset($rule['translate_attrs'][$node->name])) {
                            $type = $rule['translate_attrs'][$node->name];
                            if ($type == 'text') {
                                $this->_to_translate_text[] = $node->value;
                            } elseif ($type == 'url') {
                                $this->_to_translate_url[] = $node->value;
                            }

                        }
                    }

                });

//                $this->takeTextsFromNode($tag);

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

                    $this->fetchFields($tag, function (&$node, &$rule) use ($language) {
                        /** @var DOMElement $trueNode */
                        /** @var DOMElement $node */

                        if ($node->nodeType == XML_TEXT_NODE && isset($rule['translate_value'])
                            && $rule['translate_value']
                        ) {
                            /** @var DOMText $node */
                            if ($this->_text_translations[$node->nodeValue][$language] != null) {
                                $node->data = $this->_text_translations[$node->nodeValue][$language];
                            }
                        }

                        if ($node->nodeType == XML_ATTRIBUTE_NODE && isset($rule['translate_attrs'])) {

                            /** @var DOMAttr $node */
                            if (isset($rule['translate_attrs'][$node->name])) {
                                $type = $rule['translate_attrs'][$node->name];
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