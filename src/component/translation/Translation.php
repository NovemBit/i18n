<?php

/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation;

use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;
use NovemBit\i18n\component\translation\type\TypeTranslatorFactory;

class Translation implements interfaces\Translation
{
    /**
     * Languages of current instance
     *
     * @var array
     * */
    private array $languages;

    /**
     * Country name
     *
     * @var ?string
     * */
    private ?string $country;

    /**
     * Region name
     * */
    private ?string $region;

    public function __construct(
        private Localization $localization,
        private TypeTranslatorFactory $type_translator_factory
    ) {
    }

    /**
     * Set languages for translation
     *
     * @param  string[]  $languages  list of languages
     *
     * @return self
     * @throws UnsupportedLanguagesException
     * @throws \NovemBit\i18n\component\localization\exceptions\LanguageException
     */
    public function setLanguages(array $languages): interfaces\Translation
    {
        if ($this->localization->validateLanguages($languages)) {
            $this->languages = $languages;

            return $this;
        }

        throw new UnsupportedLanguagesException($languages);
    }

    /**
     * @param  string|null  $country  Country
     *
     * @return interfaces\Translation
     */
    public function setCountry(?string $country): interfaces\Translation
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string  $region  Region
     *
     * @return interfaces\Translation
     */
    public function setRegion(?string $region): interfaces\Translation
    {
        $this->region = $region;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Get current language
     *
     * @return mixed
     * @throws TranslationException
     */
    public function getLanguages(): array
    {
        if (isset($this->languages)) {
            return $this->languages;
        }

        throw new TranslationException('Languages not set.');
    }

    public function translateCombination(array $to_translate): array
    {
        $translations = [];

        foreach ($to_translate as $type => $nodes) {
            $translator = $this->type_translator_factory->getTypeTranslator($type);

            $translations[$type] = $translator->translate(
                $nodes,
                $this->getFromLanguage(),
                $this->localization->getFromLanguage()
            );
        }

        return $translations;
    }
}
