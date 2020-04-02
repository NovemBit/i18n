<?php
/**
 * Translation Component Exception file
 * php version 7.2.10
 *
 * @category Component\Translation\Exceptions
 * @package  Component\Translation\Exceptions
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\exceptions;


/**
 * Request Exception class
 *
 * @category Component\Translation\Exceptions
 * @package  Component\Translation\Exceptions
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class UnsupportedLanguagesException extends TranslationException
{


    /**
     * UnsupportedLanguageException constructor.
     *
     * @param array $languages
     * @param int $code
     * @param int $severity
     * @param string $filename
     * @param int $lineno
     * @param null $previous
     */
    public function __construct(
        array $languages = [],
        $code = 0,
        $severity = 1,
        $filename = __FILE__,
        $lineno = __LINE__,
        $previous = null
    ) {
        $languages = implode(', ', $languages);
        $message = 'Language not supporting {' . $languages . '}';

        parent::__construct(
            $message,
            $code,
            $severity,
            $filename,
            $lineno,
            $previous
        );
    }

}