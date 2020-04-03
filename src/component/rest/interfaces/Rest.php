<?php


namespace NovemBit\i18n\component\rest\interfaces;

use NovemBit\i18n\system\interfaces\Component;

interface Rest extends Component
{
    public const STATUS_NONE = 0;
    public const STATUS_DONE = 1;
    public const STATUS_EMPTY = -1;
    public const STATUS_ERROR = -2;

    public function start(): void;
}
