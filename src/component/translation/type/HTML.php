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
                        $parent = $node->parentNode;
                        $parent->setAttribute('_i18n', 'true');
                        $node->data = htmlspecialchars($this->translations[$type][$node->data][$language] ?? $node->data);
                    },
                    /*
                     * Callback for Attribute nodes
                     * */
                    function (&$node, $type) use ($language) {
                        /**
                         * @var DOMAttr $node
                         * @var DOMElement $parent
                         */
                        $parent = $node->parentNode;
                        $parent->setAttribute('_i18n', 'true');
                        $node->value = htmlspecialchars($this->translations[$type][$node->value][$language] ?? $node->value);
                    });

                $result[$html][$language] = $this->getHtmlParser()->save();
            }
        }

        return $result;
    }
}