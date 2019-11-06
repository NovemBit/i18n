<?php


namespace NovemBit\i18n\component\request\interfaces;


use NovemBit\i18n\component\translation\interfaces\Translation;

interface Request
{
    public function isEditor(): bool;

    public function getFromLanguage(): string;

    public function getLanguage() : string;

    public function setFromLanguage(string $from_language): void;

    public function getTranslation(): Translation;

    public function start(): void;
    public function isReady(): bool;

    public function getUrlTranslations(): ?array;
}