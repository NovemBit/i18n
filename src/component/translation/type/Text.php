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

namespace NovemBit\i18n\component\translation\type;

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
     * If element contains whitespace
     * Then after translation removing whitespace
     * To return final result as clean text
     *
     * @var bool
     * */
    public $preserve_whitespace = true;

    /**
     * If clean whitespace is true then removing whitespace
     * from  translated text before caching
     *
     * @var bool
     * */
    public $clean_whitespace = true;

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

                /**
                 * Clean whitespace
                 * */
                if ($this->clean_whitespace) {
                    $text = preg_replace('/\s+/', ' ', $text);
                }
            }
        }

        return $translations;
    }

    /**
     * Remove Whitespace
     *
     * @param string $before     Before
     * @param string $after      After
     * @param array  $translates Last result
     *
     * @return bool
     */
    public function validateAfterTranslate($before, $after, &$translates)
    {
        if (!$this->preserve_whitespace) {
            $translates[$before] = preg_replace('/\s+/', ' ', $translates[$before]);
        }

        return parent::validateAfterTranslate($before, $after, $translates);
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