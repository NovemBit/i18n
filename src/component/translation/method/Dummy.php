<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Translation
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\method;

use Exception;
use NovemBit\i18n\component\Translation;

/**
 * Dummy method of translation
 *
 * @category Class
 * @package  Method
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
     * @throws Exception
     */
    protected function doTranslate(array $texts)
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