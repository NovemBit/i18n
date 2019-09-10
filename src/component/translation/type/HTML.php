<?php

namespace NovemBit\i18n\component\translation\type;

use DOMAttr;
use DOMElement;
use DOMText;
use Exception;
use NovemBit\i18n\component\Translation;
use NovemBit\i18n\system\parsers\html\Rule;

/**
 * @property  Translation context
 */
class HTML extends Type
{
    public $type = 3;

    private $_to_translate_text = [];
    private $_to_translate_url = [];

    private $_text_translations = [];
    private $_url_translations = [];

    private $html_parser;

    public $fields_to_translate = [];

    public function init()
    {

        $this->setHtmlParser(new \NovemBit\i18n\system\parsers\HTML());

        foreach ($this->fields_to_translate as $field) {
            $text = isset($field['text']) ? $field['text'] : 'text';
            $attrs = isset($field['attrs']) ? $field['attrs'] : [];

            $rule = new Rule(
                $field['rule']['tags'] ?? null,
                $field['rule']['attrs'] ?? null,
                $field['rule']['texts'] ?? null,
                $field['rule']['mode'] ?? 'in'
            );

            $this->getHtmlParser()->addTranslateField($rule, $text, $attrs);
        }
    }

    /**
     * @return \NovemBit\i18n\system\parsers\HTML
     */
    public function getHtmlParser()
    {
        return $this->html_parser;
    }

    /**
     * @param \NovemBit\i18n\system\parsers\HTML $html_parser
     */
    public function setHtmlParser(\NovemBit\i18n\system\parsers\HTML $html_parser)
    {
        $this->html_parser = $html_parser;
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
        $result = [];

        $this->_to_translate_text = [];
        $this->_to_translate_url = [];
        $this->_text_translations = [];
        $this->_url_translations = [];

        /*
         * Finding translatable node values and attributes
         * */
        foreach ($html_list as $html) {

            $this->getHtmlParser()->load($html);

            $this->getHtmlParser()->fetch(/*
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
                }
            );
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

                $this->getHtmlParser()->load($html);

                $this->getHtmlParser()->fetch(/*
                         * Callback for Text nodes
                         * */
                    function (&$node, $type) use ($language) {
                        /** @var DOMText $node */
                        if ($this->_text_translations[$node->data][$language] != null) {
                            if ($type == 'text') {
                                if ($this->_text_translations[$node->data][$language] != null) {
                                    $node->data = htmlspecialchars($this->_text_translations[$node->data][$language]);
                                } else {
                                    /** @var DOMElement $parent */
                                    $parent = $node->parentNode;
                                    $parent->setAttribute('i18n_missing_text_text_translation', 'true');
                                }
                            } elseif ($type == 'url') {
                                if ($this->_url_translations[$node->data][$language] != null) {
                                    $node->data
                                        = htmlspecialchars($this->_url_translations[$node->data][$language]);
                                } else {
                                    /** @var DOMElement $parent */
                                    $parent = $node->parentNode;
                                    $parent->setAttribute('i18n_missing_text_url_translation', 'true');
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
                                $node->value = htmlspecialchars($this->_text_translations[$node->value][$language]);
                            } else {
                                /** @var DOMElement $parent */
                                $parent = $node->parentNode;
                                $parent->setAttribute('i18n_missing_attribute_text_translation', $node->name);
                            }
                        } elseif ($type == 'url') {
                            if ($this->_url_translations[$node->value][$language] != null) {
                                $node->value
                                    = htmlspecialchars($this->_url_translations[$node->value][$language]);
                            } else {
                                /** @var DOMElement $parent */
                                $parent = $node->parentNode;
                                $parent->setAttribute('i18n_missing_attribute_url_translation', $node->name);
                            }
                        }
                    });


                $result[$html][$language] = $this->getHtmlParser()->save();
            }
        }


        return $result;

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