<?php


namespace NovemBit\i18n\component\translation\interfaces;

use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;
use NovemBit\i18n\component\translation\method\interfaces\Method;
use NovemBit\i18n\component\translation\type\interfaces\HTML;
use NovemBit\i18n\component\translation\type\interfaces\JSON;
use NovemBit\i18n\component\translation\type\interfaces\Text;
use NovemBit\i18n\component\translation\type\interfaces\URL;
use NovemBit\i18n\component\translation\type\interfaces\XML;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\interfaces\Component;

/**
 *
 * @property Module $context
 * @property URL $url
 * @property Text $text
 * @property JSON $json
 * @property HTML $html
 * @property HTML $html_fragment
 * @property XML $xml
 * @property Method $method
 * */
interface Translation extends Component
{
    public function getFromLanguage(): string;

    /**
     * Set languages
     *
     * @param array $_languages Languages list
     *
     * @return $this
     *
     * @throws UnsupportedLanguagesException
     */
    public function setLanguages(array $_languages): self;

    public function setCountry(?string $_country): self;

    public function setRegion(?string $_region): self;

    public function getLanguages(): array;

    public function getCountry(): ?string;

    public function getRegion(): ?string;

}