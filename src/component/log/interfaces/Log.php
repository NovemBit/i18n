<?php


namespace NovemBit\i18n\component\log\interfaces;


use Psr\Log\LoggerInterface;

interface Log
{
    public function logger(): LoggerInterface;

}