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

use Doctrine\DBAL\ConnectionException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;
use NovemBit\i18n\component\translation\method\interfaces\Method;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\Module;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @property Module $context
 */
class Translation extends Component implements interfaces\Translation
{
    /**
     * Method Translator
     *
     * @var Method
     * */
    public Method $method;

    /**
     * Text Translator
     *
     * @var type\interfaces\Text
     * */
    public type\interfaces\Text $text;

    /**
     * Url Translator
     *
     * @var type\interfaces\URL
     * */
    public type\interfaces\URL $url;

    /**
     * HTML Translator
     *
     * @var type\interfaces\HTML
     * */
    public type\interfaces\HTML $html;

    /**
     * JSON Translator
     *
     * @var type\interfaces\JSON
     * */
    public type\interfaces\JSON $json;

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
     *
     * @var ?string
     * */
    private ?string $region;

    /**
     * Set languages for translation
     *
     * @param  string[]  $languages  list of languages
     *
     * @return self
     * @throws UnsupportedLanguagesException
     */
    public function setLanguages(array $languages): interfaces\Translation
    {
        if ($this->context->localization->validateLanguages($languages)) {
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

    /**
     * Get from language from Languages component
     *
     * @return string
     */
    public function getFromLanguage(): string
    {
        return $this->context->localization->getFromLanguage();
    }

    /**
     * @param  array  $to_translate
     *
     * @return array
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function translateCombination(array $to_translate): array
    {
        $translations = [];

        foreach ($to_translate as $type => $nodes) {
            $translator = $this->{$type};
            if ($translator instanceof \NovemBit\i18n\component\translation\interfaces\Translator) {
                $translations[$type] = $translator->translate($nodes);
            }
        }

        return $translations;
    }
}
