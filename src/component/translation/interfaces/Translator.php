<?php


namespace NovemBit\i18n\component\translation\interfaces;


interface Translator
{

    public function translate(array $texts);

    public function doTranslate(array $texts);

}