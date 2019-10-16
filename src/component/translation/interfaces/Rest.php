<?php


namespace NovemBit\i18n\component\translation\interfaces;


interface Rest extends Translator
{

    public function getType() : string;
}