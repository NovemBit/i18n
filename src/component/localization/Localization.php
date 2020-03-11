<?php
/**
 * Languages component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\localization;

use NovemBit\i18n\component\localization\countries\Countries;
use NovemBit\i18n\component\localization\languages\Languages;
use NovemBit\i18n\component\localization\regions\Regions;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\i18n\system\helpers\URL;
use NovemBit\i18n\component\localization\exceptions\LanguageException;

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
 * @property Module $context
 * @property Languages $languages
 * */
class Localization extends Component implements interfaces\Localization
{

    /**
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [
            'languages' => ['class' => Languages::class],
            'countries' => ['class' => Countries::class],
            'regions' => ['class' => Regions::class],
        ];
    }
}