<?php


namespace NovemBit\i18n\component\translation\interfaces;


interface Translator
{

    public function translate(array $texts, ?array &$verbose = null): array;

    public function doTranslate(array $texts): array;

    public function saveModels(
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ): void;

    public function getModels(
        $texts,
        $to_languages,
        $reverse = false
    ): array;
}