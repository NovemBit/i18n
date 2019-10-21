<?php
/**
 * System Exception file
 * php version 7.2.10
 *
 * @category System\Exceptions
 * @package  System\Exceptions
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\system\exception;

use ErrorException;

/**
 * System Exception class
 *
 * @category System\Exceptions
 * @package  System\Exceptions
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class Exception extends ErrorException implements FriendlyExceptionInterface
{

}

