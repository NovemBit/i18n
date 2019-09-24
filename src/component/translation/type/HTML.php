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

    public $data_attribute_tag = "i18n";

    private $html_parser;
    private $to_translate = [];
    private $translations = [];

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

        $this->translations = [];

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
                    $this->to_translate[$type][] = $node->data;
                },
                /*
                 * Callback for Attribute nodes
                 * */
                function (&$node, $type) {
                    /** @var DOMAttr $node */
                    $this->to_translate[$type][] = $node->value;
                }
            );
        }

        foreach ($this->to_translate as $type => $texts) {
            $this->translations[$type] = $this->context->{$type}->translate($texts);
        }

        /*
         * Replace html node values to
         * Translated values
         * */
        foreach ($html_list as $html) {

            foreach ($languages as $language) {

                $this->getHtmlParser()->load($html);

                $this->getHtmlParser()->fetch(
                /*
                 * Callback for Text nodes
                 * */
                    function (&$node, $type) use ($language) {
                        /**
                         * @var DOMText $node
                         * @var DOMElement $parent
                         */
                        $translate = false;
                        if (isset($this->translations[$type][$node->data][$language])) {
                            $translate = htmlspecialchars($this->translations[$type][$node->data][$language]);
                        }

                        $parent = $node->parentNode;

                        if ($parent->hasAttribute($this->context->context->prefix . '-text')) {
                            $text = json_decode($parent->getAttribute($this->context->context->prefix . '-text'), true);
                        } else {
                            $text = [];
                        }
                        if($translate!==false) {
                            $text[] = [$node->data, $translate];
                            $parent->setAttribute($this->context->context->prefix . '-text', json_encode($text));
                        }
                        $node->data = $translate ?? $node->data;
                    },
                    /*
                     * Callback for Attribute nodes
                     * */
                    function (&$node, $type) use ($language) {
                        /**
                         * @var DOMAttr $node
                         * @var DOMElement $parent
                         */

                        $translate = false;
                        if (isset($this->translations[$type][$node->value][$language])) {
                            $translate = htmlspecialchars($this->translations[$type][$node->value][$language]);
                        }

                        $parent = $node->parentNode;
                        if ($parent->hasAttribute($this->context->context->prefix . '-attr')) {
                            $attr = json_decode($parent->getAttribute($this->context->context->prefix . '-attr'), true);
                        } else {
                            $attr = [];
                        }
                        if($translate!==false) {
                            $attr[$node->name] = [$node->value, $translate];
                            $parent->setAttribute($this->context->context->prefix . '-attr', json_encode($attr));
                        }
                        $node->value = $translate ?? $node->value;
                    });

                $result[$html][$language] = $this->getHtmlParser()->save();
            }
        }

        return $result;
    }
}