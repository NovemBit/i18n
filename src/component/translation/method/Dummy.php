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

use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\Translation;

/**
 * Dummy method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Dummy extends Method
{

    /**
     * {@inheritdoc}
     * */
    public $exclusion_pattern = '{e-$0-e}';

    /**
     * Doing translation method
     *
     * @param array $texts Array of texts to translate
     *
     * @return array
     * @throws TranslationException
     */
    protected function doTranslate(array $texts) : array
    {

        $languages = $this->context->getLanguages();

        $result = [];

        foreach ($texts as $key => $text) {

            foreach ($languages as $language) {
                $result[(string)$text][$language] = $text . '-' . $language;
            }

        }

        return $result;

    }

}