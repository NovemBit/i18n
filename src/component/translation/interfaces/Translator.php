<?php


namespace NovemBit\i18n\component\translation\interfaces;


interface Translator
{

    public function translate(
        array $texts,
        ?array &$verbose = null,
        bool $only_saved = false
    ): array;


    public function saveModels(
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ): void;

    public function getModels(
        array $texts,
        string $from_language,
        array $to_languages,
        bool $reverse = false
    ): array;

    public function reTranslate(
        array $texts
    ): array;
}