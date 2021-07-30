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

use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\Translator;

/**
 * Main Translation method abstract
 * Any method of translation must extends this class
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
abstract class Method extends Translator implements interfaces\Method
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'method';

    /**
     * @var bool
     */
    public $cache_result = true;

    /**
     * @var bool
     */
    public $save_translations = true;

    /**
     * @var bool
     */
    public $get_translations_from_db = true;

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\Method::class;

    /**
     * {@inheritdoc}
     * */
    public $validation = false;
}
