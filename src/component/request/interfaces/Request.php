<?php

namespace NovemBit\i18n\component\request\interfaces;

use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\interfaces\Component;

interface Request
{
    public function isEditor(): bool;

    public function getFromLanguage(): string;

    public function getLanguage(): string;

    public function getTranslation(): Translation;

    public function getSourceUrl(): ?string;

    public function getDestination(): string;

    public function start(): void;

    public function getVerbose(): array;

    public function getOrigRequestUri(): string;

    public function isReady(): bool;

    public function getUrlTranslations(): ?array;

    public function getEditorUrlTranslations(): array;

    public function isAllowEditor(): bool;

    public function getActiveLanguages(): array;

    public function getAcceptLanguages(bool $assoc = false): array;

    public function isGlobalDomain(): bool;
}
