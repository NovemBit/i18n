<?php


namespace NovemBit\i18n\component\translation\interfaces;

use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;

interface Translation
{
    /**
     * @throws UnsupportedLanguagesException
     */
    public function setLanguages(array $languages): self;

    public function setCountry(?string $country): self;

    public function setRegion(?string $region): self;

    public function getLanguages(): array;

    public function getCountry(): ?string;

    public function getRegion(): ?string;

    public function translateCombination(array $to_translate): array;
}
