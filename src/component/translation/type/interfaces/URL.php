<?php
/**
 * URL translation type interface file
 * php version 7.2.10
 *
 * @category Component\Translation\Type\Interface
 * @package  Component\Translation\Type\Interface
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type\interfaces;

/**
 * URL translation type interface
 *
 * @category Component\Translation\Type\Interface
 * @package  Component\Translation\Type\Interface
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
interface URL extends TypeInterface
{

    public function isPathTranslation(): bool;
}