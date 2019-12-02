<?php


namespace NovemBit\i18n\component\rest\interfaces;


use NovemBit\i18n\system\interfaces\Component;

interface Rest extends Component
{
    const STATUS_NONE = 0;
    const STATUS_DONE = 1;
    const STATUS_EMPTY = -1;
    const STATUS_ERROR = -2;
    public function start(): void;
}