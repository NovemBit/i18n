<?php

namespace NovemBit\i18n\component\translation\interfaces;

use Doctrine\DBAL\ConnectionException;
use NovemBit\i18n\system\interfaces\Component;
use Psr\SimpleCache\InvalidArgumentException;

interface Translator
{

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * */
    public function translate(
        array $texts,
        string $from_language,
        array $to_languages,
        ?array &$verbose = null,
        bool $only_saved = false,
        bool $ignore_cache = false
    ): array;


    public function saveModels(
        array $translations,
        string $from_language,
        int $level,
        bool $overwrite,
        array &$result = []
    ): void;

    public function getSavedTranslations(
        array $texts,
        string $from_language,
        array $to_languages
    ): array;

    public function reTranslate(
        array $texts,
        string $from_language,
        array $to_languages,
    ): array;

    public function isCacheResult(): ?bool;

    public function setCacheResult(bool $cache_status): void;

    public function getHelperAttributes(): array;

    public function setHelperAttributes(array $attributes): void;
}