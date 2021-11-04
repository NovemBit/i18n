<?php


namespace NovemBit\i18n\component\rest\interfaces;


interface Rest
{
    public const STATUS_NONE = 0;
    public const STATUS_DONE = 1;
    public const STATUS_EMPTY = -1;
    public const STATUS_ERROR = -2;

    public function start(): void;
}
