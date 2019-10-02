<?php


namespace NovemBit\i18n\component\translation\Interfaces;


use NovemBit\i18n\component\translation\Method\Method;
use NovemBit\i18n\component\translation\Type\Type;

interface Translation
{

    public function getMethod(): Method;

    public function getText(): Type;

    public function getHtml(): Type;

    public function getUrl(): Type;

    public function getJson(): Type;


}