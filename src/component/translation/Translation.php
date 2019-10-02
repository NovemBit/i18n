<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation;


use NovemBit\i18n\component\translation\Method\Method;
use NovemBit\i18n\component\translation\Type\HTML;
use NovemBit\i18n\component\translation\Type\JSON;
use NovemBit\i18n\component\translation\Type\Text;
use NovemBit\i18n\component\translation\Type\URL;
use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\Module;

/**
 * Translation component
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Module $context
 */
class Translation extends Component implements Interfaces\Translation
{

    /**
     * Method Translator
     *
     * @var Method
     * */
    public $method;

    /**
     * Text Translator
     *
     * @var Text
     * */
    public $text;

    /**
     * Url Translator
     *
     * @var URL
     * */
    public $url;

    /**
     * HTML Translator
     *
     * @var HTML
     * */
    public $html;

    /**
     * JSON Translator
     *
     * @var JSON
     * */
    public $json;

    /**
     * Languages of current instance
     *
     * @var array
     * */
    private $_languages;

    /**
     * Set languages for translation
     *
     * @param array|string $_languages list of languages
     *
     * @return Translation
     * @throws Exception
     */
    public function setLanguages($_languages)
    {
        if (is_string($_languages)) {
            $_languages = [$_languages];
        }

        if ($this->context->languages->validateLanguages($_languages)) {
            $this->_languages = $_languages;

            return $this;
        } else {
            throw new Exception('Language not supporting.');
        }
    }

    /**
     * Get current language
     *
     * @return mixed
     * @throws Exception
     */
    public function getLanguages()
    {
        if (isset($this->_languages)) {
            return $this->_languages;
        } else {
            throw new Exception('Languages not set.');
        }
    }

    /**
     * Get from language from Languages component
     *
     * @return mixed
     * @throws Exception
     */
    public function getFromLanguage()
    {
        return $this->context->languages->getFromLanguage();
    }


}