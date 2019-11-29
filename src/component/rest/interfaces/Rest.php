<?php


namespace NovemBit\i18n\component\rest\interfaces;


use NovemBit\i18n\system\interfaces\Component;

interface Rest extends Component
{
    public function start(): void;
}