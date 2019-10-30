<?php


namespace NovemBit\i18n\component\translation\interfaces;

use NovemBit\i18n\component\translation\method\interfaces\Method;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\component\translation\type\interfaces\Text;
use NovemBit\i18n\component\translation\type\interfaces\URL;
use NovemBit\i18n\Module;

/**
 *
 * @property Module $context
 * @property URL $url
 * @property Text $text
 * @property HTML $html
 * @property Method $method
 * */
interface Translation
{
    public function getFromLanguage(): string;

    public function setLanguages($_languages): self;

    public function getLanguages(): array;

}