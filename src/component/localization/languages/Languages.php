<?php

namespace NovemBit\i18n\component\localization\languages;

use NovemBit\i18n\component\localization\exceptions\LanguageException;
use NovemBit\i18n\component\localization\Localization;
use NovemBit\i18n\component\localization\LocalizationType;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\i18n\system\helpers\URL;

/**
 * Setting default languages
 *  from language - main website content language
 *  default language - default language for request
 *  accept languages - languages list for translations
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Localization $context
 * */
class Languages extends LocalizationType implements interfaces\Languages
{

    public $all;

    /**
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [
            'all' => \NovemBit\i18n\system\helpers\Languages::getData()
        ];
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function getLanguagesMap(string $key, string $value): array
    {
        return Arrays::map($this->all, $key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $url Simple URL
     *
     * @return string|null
     * @deprecated
     * @throws LanguageException
     */
    public function getLanguageFromUrl(string $url): ?string
    {
        return $this->context->getLanguageFromUrl($url);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $url Simple url
     * @deprecated
     * @return string
     */
    public function removeScriptNameFromUrl(string $url): string
    {
        return $this->context->removeScriptNameFromUrl($url);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $url Simple url
     * @param string $language language code
     * @param string|null $base_domain Base domain name
     *
     * @return null|string
     * @throws LanguageException
     * @deprecated
     */
    public function addLanguageToUrl(
        string $url,
        string $language,
        ?string $base_domain = null
    ): ?string {
        return $this->context->addLanguageToUrl($url, $language, $base_domain);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $language language code
     *
     * @return bool
     * @throws LanguageException
     * @deprecated use `localization->validateLanguage()`
     */
    public function validateLanguage(string $language): bool
    {
        return $this->context->validateLanguage($language);
    }

    /**
     * {@inheritDoc}
     *
     * @param string[] $languages language codes
     *
     * @return bool
     * @throws LanguageException
     * @deprecated use `localization->validateLanguages()`
     */
    public function validateLanguages(array $languages): bool
    {
        return $this->context->validateLanguages($languages);
    }

    /**
     * {@inheritDoc}
     *
     * @param bool $assoc include whole data
     *
     * @return array|null
     * @throws LanguageException
     * @deprecated use localization->getAcceptLanguages()
     */
    public function getAcceptLanguages(
        bool $assoc = false,
        ?string $base_domain = null
    ): array {
        return $this->context->getAcceptLanguages($base_domain, $assoc);
    }

    /**
     * @param string $language_key Language code
     *
     * @return array
     *
     * @throws LanguageException
     */
    public function getLanguageData(string $language_key): array
    {
        return [
            'name' => $this->getLanguageNameByCode($language_key),
            'flag' => $this->getLanguageFlagByCode($language_key),
            'native' => $this->getLanguageNativeNameByCode($language_key),
            'direction' => $this->getLanguageDirectionByCode($language_key),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     * @deprecated
     */
    public function getFromLanguage(): string
    {
        return $this->context->getFromLanguage();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return array
     */
    public function getLocalizationConfig(?string $base_domain = null): array
    {
        return $this->context->getConfig($base_domain);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain name
     *                                 (usually $_SERVER['HTTP_HOST'])
     *
     * @return string
     * @deprecated
     * @throws LanguageException
     */
    public function getDefaultLanguage(?string $base_domain = null): string
    {
        return $this->context->getDefaultLanguage($base_domain);
    }

    /**
     * {@inheritDoc}
     * @deprecated
     * @return mixed
     */
    public function getLanguageQueryKey(): string
    {
        return $this->context->getLanguageQueryKey();
    }

    /**
     * {@inheritDoc}
     *
     * @param string $from_language From language code
     *
     * @return void
     * @deprecated
     * @throws LanguageException
     */
    public function setFromLanguage(string $from_language): void
    {
        $this->context->setFromLanguage($from_language);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     *
     * @return mixed|null
     * @throws LanguageException
     */
    public function getLanguageNameByCode(string $code): string
    {
        $name = $this->getByPrimary(
            $code,
            'alpha1',
            'name'
        );

        if ($name === null) {
            throw new LanguageException("Language name property not found!");
        }
        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     *
     * @return mixed|null
     */
    public function getLanguageDirectionByCode(string $code): string
    {
        $dir = $this->getByPrimary(
            $code,
            'alpha1',
            'dir'
        );

        return $dir == null ? 'ltr' : 'rtl';
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     *
     * @return mixed|null
     * @throws LanguageException
     */
    public function getLanguageNativeNameByCode(string $code): string
    {
        $name = $this->getByPrimary(
            $code,
            'alpha1',
            'native'
        ) ?? null;

        if ($name === null) {
            throw new LanguageException("Language native property not found!");
        }
        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $code Language code
     * @param bool $rectangle Rectangle format image
     * @param bool $html Return html <img src="..
     *
     * @return string
     * @throws LanguageException
     */
    public function getLanguageFlagByCode(
        string $code,
        $rectangle = true,
        $html = false
    ): string {
        $flag = $this->getByPrimary(
            $code,
            'alpha1',
            'countries'
        )[0] ?? null;

        if ($flag === null) {
            throw new LanguageException("Language flag property not found!");
        }

        $name = $this->getLanguageNameByCode($code);

        $size = $rectangle ? "4x3" : "1x1";
        $path = __DIR__ . '/../assets/images/flags/' . $size . '/' . $flag . '.svg';

        $base64 = sprintf(
            "data:image/svg+xml;base64,%s",
            base64_encode(file_get_contents($path))
        );

        return $html
            ? "<img alt=\"$name\" title=\"$name\" src=\"$base64\"/>"
            : $base64;
    }
}
