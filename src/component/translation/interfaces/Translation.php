<?php


namespace NovemBit\i18n\component\translation\interfaces;


interface Translation
{
    public function getFromLanguage() : string;

    public function setLanguages($_languages) : void;

    public function getLanguages(): array;

}