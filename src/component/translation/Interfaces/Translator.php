<?php


namespace NovemBit\i18n\component\translation\Interfaces;


interface Translator
{

    public function translate(array $texts);

    public function doTranslate(array $texts);

}