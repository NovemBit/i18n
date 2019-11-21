<?php


namespace NovemBit\i18n\component\request\interfaces;


use NovemBit\i18n\component\translation\interfaces\Translation;

interface Request
{
    public function isEditor(): bool;

    public function getFromLanguage(): string;

    public function getLanguage(): string;

    public function getTranslation(): Translation;

    public function getSourceUrl(): ?string;

    public function getDestination(): string;

    public function start(): void;

    public function isReady(): bool;

    public function getUrlTranslations(): ?array;
}