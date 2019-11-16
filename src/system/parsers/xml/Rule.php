<?php
/**
 * HTML parser
 * php version 7.2.10
 *
 * @category System\Parsers\HTML
 * @package  System\Parsers\HTML
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\system\parsers\xml;

use DOMElement;
use DOMText;

/**
 * HTML parser with callback function
 * Using PHP Dom parser
 *
 * @category System\Parsers\HTML
 * @package  System\Parsers\HTML
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class Rule
{
    /**
     * Check if tagName|attribute|text
     * exists in array
     *
     * @var string
     * */
    const IN = 'in';

    /**
     * Check tagName|attribute|text regex validation
     *
     * @var string
     * */
    const REGEX = 'regex';

    /**
     * Check equality for tagName|attribute|text
     *
     * @var string
     * */
    const EQ = 'EQ';

    /**
     * Check tagName|attribute|text contains
     *
     * @var string
     * */
    const CONTAINS = 'contains';

    /**
     * HTML tags
     *
     * @var array
     * */
    private $_tags;

    /**
     * Html attr nodes
     *
     * @var array
     * */
    private $_attrs;

    /**
     * Html Text nodes
     *
     * @var array
     * */
    private $_texts;

    /**
     * Mode of rule join
     *
     * @var string
     * */
    private $_mode;

    /**
     * Rule constructor.
     *
     * @param array|null $tags  Tags array
     * @param array|null $attrs Attributes array
     * @param array|null $texts Texts array
     * @param string     $mode  Mode of join
     */
    public function __construct(
        array $tags = null,
        array $attrs = null,
        array $texts = null,
        $mode = self::IN
    ) {
        $this->_setTags($tags);
        $this->_setAttrs($attrs);
        $this->_setTexts($texts);
        $this->_setMode($mode);
    }

    /**
     * Get Tags
     *
     * @return mixed
     */
    private function _getTags()
    {
        return $this->_tags;
    }

    /**
     * Set Tags
     *
     * @param array $_tags Tags array
     *
     * @return void
     */
    private function _setTags($_tags = null)
    {
        $this->_tags = $_tags;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    private function _getAttrs()
    {
        return $this->_attrs;
    }

    /**
     * Set attributes
     *
     * @param array $_attrs Attributes array
     *
     * @return void
     */
    private function _setAttrs($_attrs = null)
    {
        $this->_attrs = $_attrs;
    }

    /**
     * Get Texts
     *
     * @return array
     */
    private function _getTexts()
    {
        return $this->_texts;
    }

    /**
     * Set texts
     *
     * @param mixed $_texts Texts array
     *
     * @return void
     */
    private function _setTexts($_texts = null)
    {
        $this->_texts = $_texts;
    }

    /**
     * Validate Tag
     *
     * @param DOMElement $node Element Node
     *
     * @return bool
     */
    private function _validateTag($node)
    {
        if (!$this->_getTags()) {
            return true;
        }

        if ($this->_getMode() == self::REGEX) {
            $regex_status = false;
            foreach ($this->_getTags() as $pattern) {
                if (preg_match($pattern, $node->tagName)) {
                    $regex_status = true;
                    break;
                }
            }
            if (!$regex_status) {
                return false;
            }
        } elseif ($this->_getMode() == self::IN) {
            if (!in_array($node->tagName, $this->_getTags())) {
                return false;
            }
        } elseif ($this->_getMode() == self::CONTAINS) {
            if (!(strpos($this->_getTags(), $node->tagName) !== false)) {
                return false;
            }
        } elseif ($this->_getMode() == self::EQ) {
            if ($node->tagName != $this->_getTags()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate Attr Node
     *
     * @param DOMElement $node Attr tag
     *
     * @return bool
     */
    private function _validateAttrs($node)
    {
        if (!$this->_getAttrs()) {
            return true;
        }

        foreach ($this->_getAttrs() as $attribute => $values) {

            if ($node->hasAttribute($attribute)) {
                $attribute_value = $node->getAttribute($attribute);
            } else {
                $attribute_value = '';
            }

            if ($this->_getMode() == self::REGEX) {

                $regex_status = false;
                foreach ($values as $pattern) {
                    if (preg_match($pattern, $attribute_value)) {
                        $regex_status = true;
                        break;
                    }
                }
                if (!$regex_status) {
                    return false;
                }
            } elseif ($this->_getMode() == self::IN) {
                if (!in_array('*', $values)
                    && !in_array(
                    $attribute_value, $values
                )
                ) {
                    return false;
                }
            } elseif ($this->_getMode() == self::CONTAINS) {
                if (!(strpos(
                    $values,
                    $attribute_value
                ) !== false)
                ) {
                    return false;
                }
            } elseif ($this->_getMode() == self::EQ) {
                if ($attribute_value != $values) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Validate TextNodes of Node
     *
     * @param DOMElement $node Node element
     *
     * @return bool
     */
    private function _validateTexts($node)
    {
        if (!$this->_getTexts()) {
            return true;
        }
        foreach ($node->childNodes as $child_node) {
            if ($child_node->nodeType == XML_TEXT_NODE) {

                /**
                 * Check mode of join
                 *
                 * @var DOMText $child_node
                 */
                if ($this->_getMode() == self::REGEX) {
                    $regex_status = false;
                    foreach ($this->_getTexts() as $pattern) {
                        if (preg_match($pattern, $child_node->data)) {
                            $regex_status = true;
                            break;
                        }
                    }
                    if (!$regex_status) {
                        return false;
                    }
                } elseif ($this->_getMode() == self::IN) {
                    if (!in_array($child_node->data, $this->_getTexts())) {
                        return false;
                    }
                } elseif ($this->_getMode() == self::CONTAINS) {
                    if (!(strpos($this->_getTexts(), $child_node->data) !== false)) {
                        return false;
                    }
                } elseif ($this->_getMode() == self::EQ) {
                    if ($child_node->data != $this->_getTexts()) {
                        return false;
                    }
                }
            }
        }


        return true;
    }

    /**
     * Main Validate Method
     *
     * @param DOMElement $node Node to validate
     *
     * @return bool
     */
    public function validate(DOMElement $node):bool
    {

        /*
         * Validate node tag
         * */
        if (!$this->_validateTag($node)) {
            return false;
        }

        /*
         * Validate node attributes
         * */
        if (!$this->_validateAttrs($node)) {
            return false;
        }

        /**
         * Validate node text
         */
        if (!$this->_validateTexts($node)) {
            return false;
        }

        return true;
    }

    /**
     * Get mode of join
     *
     * @return mixed
     */
    private function _getMode()
    {
        return $this->_mode;
    }

    /**
     * Setting join mode
     *
     * @param mixed $_mode Join Mode
     *
     * @return void
     */
    private function _setMode($_mode)
    {
        $this->_mode = $_mode;
    }

}
