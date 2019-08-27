<?php


namespace NovemBit\i18n\component;


use NovemBit\i18n\component\translation\method\Method;
use NovemBit\i18n\component\translation\type\HTML;
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
 * @property Module $context
 */
class Translation extends Component
{

    private $languages;

    function init()
    {

    }

    /**
     * @param array|string $languages
     *
     * @return Translation
     * @throws \Exception
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


}