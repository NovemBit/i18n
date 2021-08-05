<?php

/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\method;

use NovemBit\i18n\component\translation\Translation;

/**
 * Manual method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Manual extends Method
{

    /**
     * {@inheritdoc}
     * */
    public string $exclusion_pattern = '{e-$0-e}';

    /**
     * @var bool
     */
    public bool $save_translations = false;

    /**
     * Doing translation method
     *
     * @param array $texts Array of texts to translate
     *
     * @param string $from_language
     * @param array $to_languages
     * @param bool $ignore_cache
     * @return array
     */
    protected function doTranslate(
        array $texts,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {

        $result = [];

        foreach ($texts as $key => $text) {
            foreach ($to_languages as $language) {
                $result[(string)$text][$language] = $text;
            }
        }

        return $result;
    }
}
