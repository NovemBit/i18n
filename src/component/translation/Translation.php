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


use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\method\interfaces\Method;
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
class Translation extends Component implements interfaces\Translation
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
     * @var type\interfaces\Text
     * */
    public $text;

    /**
     * Url Translator
     *
     * @var type\interfaces\URL
     * */
    public $url;

    /**
     * HTML Translator
     *
     * @var type\interfaces\HTML
     * */
    public $html;

    /**
     * JSON Translator
     *
     * @var type\interfaces\JSON
     * */
    public $json;

    /**
     * Languages of current instance
     *
     * @var array
     * */
    private $_languages;

    /**
     * Country name
     *
     * @var string
     * */
    private $_country;

    /**
     * Set languages for translation
     *
     * @param array|string $_languages list of languages
     *
     * @return self
     * @throws TranslationException
     */
    public function setLanguages(array $_languages) : interfaces\Translation
    {
        if ($this->context->languages->validateLanguages($_languages)) {
            $this->_languages = $_languages;
            return $this;
        } else {
            throw new TranslationException('Language not supporting.');
        }
    }

    public function setCountry(string $_country): interfaces\Translation{
        $this->_country = $_country;
        return $this;
    }

    public function getCountry():string {
        return $this->_country;
    }

    /**
     * Get current language
     *
     * @return mixed
     * @throws TranslationException
     */
    public function getLanguages() : array
    {
        if (isset($this->_languages)) {
            return $this->_languages;
        } else {
            throw new TranslationException('Languages not set.');
        }
    }

    /**
     * Get from language from Languages component
     *
     * @return string
     */
    public function getFromLanguage() : string
    {
        return $this->context->languages->getFromLanguage();
    }
}