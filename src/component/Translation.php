<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Composer
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */
namespace NovemBit\i18n\component;


use NovemBit\i18n\component\translation\method\Method;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\i18n\component\translation\type\Text;
use NovemBit\i18n\component\translation\type\URL;
use NovemBit\i18n\system\exception\Exception;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;

/**
 * @property Method method
 * @property Text   $text
 * @property URL    $url
 * @property HTML   $html
 * @property JSON   $json
 * @property Module $context
 */
class Translation extends Component
{

    private $languages;


    /**
     * @param array|string $languages
     *
     * @return Translation
     * @throws Exception
     */
    public function setLanguages($languages)
    {
        if (is_string($languages)) {
            $languages = [$languages];
        }

        if ($this->context->languages->validateLanguages($languages)) {
            $this->languages = $languages;

            return $this;
        } else {
            throw new Exception('Language not supporting.');
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLanguages()
    {
        if (isset($this->languages)) {
            return $this->languages;
        } else {
            throw new Exception('Languages not set.');
        }
    }

    public function getFromLanguage()
    {
        return $this->context->languages->getFromLanguage();
    }
}