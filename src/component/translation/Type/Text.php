<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\Type;

use Exception;
use NovemBit\i18n\component\translation\Translation;

/**
 * Text type for Translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Text extends Type
{

    /**
     * Type using as DB type column value
     *
     * @var int
     * */
    public $type = 1;

    /**
     * {@inheritdoc}
     * */
    public $validation = true;

    /**
     * Dont translate regexp patterns
     *
     * @var string[]
     * */
    public $dont_translate_patterns = [

        /*
         * Dont translate texts that contains less then 3 characters
         * Or not contains letters
         * */
        '^(([^\p{L}]+)|(.{1,2}))$'
    ];

    /**
     * Doing translate method
     *
     * @param array $texts List of texts to translate
     *
     * @return array
     * @throws Exception
     */
    public function doTranslate(array $texts)
    {


        $translations = $this->context->method->translate($texts);

        foreach ($translations as $source => &$translation) {
            foreach ($translation as $language => &$text) {
                $text = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML401);
            }
        }

        return $translations;
    }

    /**
     * Validate text before translate
     *
     * @param string $text Referenced text variable to translate
     *
     * @return bool
     */
    public function validateBeforeTranslate(&$text)
    {

        foreach ($this->dont_translate_patterns as $pattern) {
            if (preg_match("/$pattern/", $text)) {

                return false;
            }
        }

        return parent::validateBeforeTranslate($text);
    }
}