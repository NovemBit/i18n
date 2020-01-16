<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type;

use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\Strings;

/**
 * Text type for Translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class Text extends Type
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'text';

    public $cache_result = true;

    /**
     * {@inheritdoc}
     * */
    public $validation = true;

    /**
     * Dont translate regexp patterns
     *
     * @var string[]
     * */
    public $dont_translate_patterns = [

        /*
         * Dont translate texts that contains less then 3 characters
         * Or not contains letters
         * */
        '^(([^\p{L}]+)|(.{1,2}))$',

        /**
         * Do not translate texts that underlined phrase like this
         * some_underlined_text
         * */
        '^\w+?(?>_\w+)+$',

        /**
         * Dp not translate texts that is file name
         * my-custom-file.docx
         * */
        '^\w+?(?>-\w+)+(?>\.(?>mp3|mp4|wma|wav|jpg|jpeg|png|gif|bmp|webp|pdf|doc|docx|txt|rar|zip|tar|gz))$',

        /**
         * Do not translate text that contains only url
         * */
        '^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$'
    ];

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\Text::class;

    /**
     * Doing translate method
     *
     * @param array $texts List of texts to translate
     *
     * @param string $from_language
     * @param array $to_languages
     * @param bool $ignore_cache
     *
     * @return array
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function doTranslate(
        array $texts,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {

        $translator = $this->context->method;

        $translations = $translator->translate(
            $texts,
            $verbose,
            false,
            $ignore_cache
        );

        foreach ($translations as $source => &$translation) {
            foreach ($translation as $language => &$text) {
                $text = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML401);
            }
        }

        return $translations;
    }

    /**
     * Reset whitespace
     *
     * @param string $before Before
     * @param string $after After
     * @param array $translates Last result
     * @param array|null $verbose Verbose
     *
     * @return bool
     */
    protected function validateAfterTranslate(
        $before,
        $after,
        &$translates,
        ?array &$verbose
    ): bool {

        Strings::getStringsDifference(
            $before,
            $after,
            $prefix,
            $suffix
        );

        $verbose[$before]['prefix'] = $prefix;
        $verbose[$before]['suffix'] = $suffix;

        if ($before != $after && isset($translates[$before])) {
            foreach ($translates[$before] as $language => &$translate) {
                $translate = $prefix . $translate . $suffix;
            }

        }

        return parent::validateAfterTranslate(
            $before,
            $after,
            $translates,
            $verbose
        );
    }


    /**
     * Using dont_translate_patterns to ignore texts
     * Clearing whitespace
     *
     * @param string $text Referenced text variable to translate
     *
     * @return bool
     */
    protected function validateBeforeTranslate(&$text): bool
    {

        foreach ($this->dont_translate_patterns as $pattern) {
            if (preg_match("/$pattern/", $text)) {
                return false;
            }
        }

        $text = preg_replace('/^\s+|\s+$/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return parent::validateBeforeTranslate($text);
    }

}