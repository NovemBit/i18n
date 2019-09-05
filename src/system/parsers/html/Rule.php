<?php

namespace NovemBit\i18n\system\parsers\html;

use DOMElement;

class Rule
{
    private $tags;

    private $attrs;

    private $texts;

    public function __construct(array $tags = null, array $attrs = null, array $texts = null)
    {
        $this->setTags($tags);
        $this->setAttrs($attrs);
        $this->setTexts($texts);
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags = null)
    {
        if ($tags != null) {
            $tags = array_unique($tags, SORT_REGULAR);
        }
        $this->tags = $tags;
    }

    /**
     * @param $tag
     */
    public function addTag($tag)
    {
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * @return array
     */
    public function getAttrs()
    {
        return $this->attrs;
    }

    /**
     * @param array $attrs
     */
    public function setAttrs(array $attrs = null)
    {
        if ($attrs != null) {
            $attrs = array_unique($attrs, SORT_REGULAR);
        }
        $this->attrs = $attrs;
    }

    /**
     * @param $attr
     * @param $value
     */
    public function addAttr($attr, $value)
    {
        if (!in_array($attr, $this->tags)) {
            $this->attrs[$attr] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @param mixed $texts
     */
    public function setTexts(array $texts = null)
    {
        $this->texts = $texts;
    }

    /**
     * @param string $text
     */
    public function addText(string $text)
    {
        if (!in_array($text, $this->texts)) {
            $this->texts[] = $text;
        }
    }

    /**
     * @param DOMElement $node
     *
     * @return bool
     */
    private function validateTag($node)
    {
        if ($this->getTags() && !in_array($node->tagName, $this->getTags())) {
            return false;
        }

        return true;
    }

    /**
     * @param DOMElement $node
     *
     * @return bool
     */
    private function validateAttrs($node)
    {
        if ($this->getAttrs()) {
            foreach ($this->getAttrs() as $attribute => $values) {
                if (!$node->hasAttribute($attribute)) {
                    return false;
                } else {
                    if (!in_array('*', $values) && !in_array($node->getAttribute($attribute), $values)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param DOMElement $node
     *
     * @return bool
     */
    private function validateTexts($node)
    {
        if ($this->getTexts()) {
            foreach ($node->childNodes as $child_node) {
                if ($child_node->nodeType == XML_TEXT_NODE) {
                    /** @var \DOMText $child_node */
                    if (!in_array($child_node->data, $this->getTexts())) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param DOMElement $node
     *
     * @return bool
     */
    public function validate(DOMElement $node)
    {

        /*
         * Validate node tag
         * */
        if (!$this->validateTag($node)) {
            return false;
        }

        /*
         * Validate node attributes
         * */
        if (!$this->validateAttrs($node)) {
            return false;
        }

        /**
         * Validate node text
         *
         */
        if (!$this->validateTexts($node)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->negative;
    }

    /**
     * @param bool $negative
     */
    public function setNegative(bool $negative)
    {
        $this->negative = $negative;
    }


}