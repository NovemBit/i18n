<?php

namespace NovemBit\i18n\component\localization\languages;

use NovemBit\i18n\component\localization\exceptions\LanguageException;
use NovemBit\i18n\component\localization\LocalizationType;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\i18n\system\helpers\Languages as LanguagesHelper;

/**
 * Localization language sub component
 *
 * @category Component\Localization
 * @package  Component\Localization
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 * */
class Languages extends LocalizationType implements interfaces\Languages
{
    public function __construct(){
        $this->setAll(LanguagesHelper::getData());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function getLanguagesMap(string $key, string $value): array
    {
        return Arrays::map($this->getAll(), $key, $value);
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
            throw new LanguageException('Language name property not found!');
        }
        return $name;
    }

    /**
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

        if (!in_array($dir, ['rtl','ltr'])) {
            $dir = 'ltr';
        }

        return $dir;
    }

    /**
     * @param string $code Language code
     * @return mixed|null
     * @throws LanguageException
     */
    public function getLanguageNativeNameByCode(string $code): string
    {
        $name = $this->getByPrimary(
            $code,
            'alpha1',
            'native'
        );

        if ($name === null) {
            throw new LanguageException('Language native property not found!');
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
        bool $rectangle = true,
        bool $html = false
    ): string {
        $flag = $this->getByPrimary(
            $code,
            'alpha1',
            'countries'
        )[0] ?? null;

        if ($flag === null) {
            throw new LanguageException('Language flag property not found!');
        }

        $name = $this->getLanguageNameByCode($code);

        $size = $rectangle ? '4x3' : '1x1';
        $path = __DIR__ . '/../assets/images/flags/' . $size . '/' . $flag . '.svg';

        $base64 = sprintf(
            'data:image/svg+xml;base64,%s',
            base64_encode(file_get_contents($path))
        );

        return $html
            ? "<img alt=\"$name\" title=\"$name\" src=\"$base64\"/>"
            : $base64;
    }
}
