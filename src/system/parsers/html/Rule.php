<?php

namespace NovemBit\i18n\system\parsers\html;

use DOMElement;
use DOMText;

class Rule
{
    /*
     * Check if tagName|attribute|text
     * exists in array
     * */
    const IN = 'in';

    /*
     * tagName|attribute|text regex validation
     * */
    const REGEX = 'regex';

    /*
     * Check equality for tagName|attribute|text
     * */
    const EQ = 'EQ';

    /*
     * Check tagName|attribute|text contains
     * */
    const CONTAINS = 'contains';

    private $tags;

    private $attrs;

    private $texts;

    private $mode;

    /**
     * Rule constructor.
     * @param array|null $tags
     * @param array|null $attrs
     * @param array|null $texts
     * @param string $mode
     */
    public function __construct(array $tags = null, array $attrs = null, array $texts = null, $mode = self::IN)
    {
        $this->setTags($tags);
        $this->setAttrs($attrs);
        $this->setTexts($texts);
        $this->setMode($mode);
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags = null)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getAttrs()
    {
        return $this->attrs;
    }

    /**
     * @param array $attrs
     */
    public function setAttrs($attrs = null)
    {
        $this->attrs = $attrs;
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
    public function setTexts($texts = null)
    {
        $this->texts = $texts;
    }

    /**
     * @param DOMElement $node
     *
     * @return bool
     */
    private function validateTag($node)
    {
        if (!$this->getTags()) {
            return true;
        }

        if ($this->getMode() == self::REGEX) {
            $regex_status = false;
            foreach ($this->getTags() as $pattern) {
                if (preg_match($pattern, $node->tagName)) {
                    $regex_status = true;
                    break;
                }
            }
            if (!$regex_status) {
                return false;
            }
        } elseif ($this->getMode() == self::IN) {
            if (!in_array($node->tagName, $this->getTags())) {
                return false;
            }
        } elseif ($this->getMode() == self::CONTAINS) {
            if (!(strpos($this->getTags(), $node->tagName) !== false)) {
                return false;
            }
        } elseif ($this->getMode() == self::EQ) {
            if ($node->tagName != $this->getTags()) {
                return false;
            }
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
        if (!$this->getAttrs()) {
            return true;
        }

        foreach ($this->getAttrs() as $attribute => $values) {
            if (!$node->hasAttribute($attribute)) {
                return false;
            } else {
                if ($this->getMode() == self::REGEX) {
                    $regex_status = false;
                    foreach ($values as $pattern) {
                        if (preg_match($pattern, $node->getAttribute($attribute))) {
                            $regex_status = true;
                            break;
                        }
                    }
                    if (!$regex_status) {
                        return false;
                    }
                } elseif ($this->getMode() == self::IN) {
                    if (!in_array('*', $values) && !in_array($node->getAttribute($attribute), $values)) {
                        return false;
                    }
                } elseif ($this->getMode() == self::CONTAINS) {
                    if (!(strpos($values, $node->getAttribute($attribute)) !== false)) {
                        return false;
                    }
                } elseif ($this->getMode() == self::EQ) {
                    if ($node->getAttribute($attribute) != $values) {
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
        if (!$this->getTexts()) {
            return true;
        }
        foreach ($node->childNodes as $child_node) {
            if ($child_node->nodeType == XML_TEXT_NODE) {

                /** @var DOMText $child_node */
                if ($this->getMode() == self::REGEX) {
                    $regex_status = false;
                    foreach ($this->getTexts() as $pattern) {
                        if (preg_match($pattern, $child_node->data)) {
                            $regex_status = true;
                            break;
                        }
                    }
                    if (!$regex_status) {
                        return false;
                    }
                } elseif ($this->getMode() == self::IN) {
                    if (!in_array($child_node->data, $this->getTexts())) {
                        return false;
                    }
                } elseif ($this->getMode() == self::CONTAINS) {
                    if (!(strpos($this->getTexts(), $child_node->data) !== false)) {
                        return false;
                    }
                } elseif ($this->getMode() == self::EQ) {
                    if ($child_node->data != $this->getTexts()) {
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
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

}