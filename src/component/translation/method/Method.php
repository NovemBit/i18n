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

use NovemBit\i18n\component\translation\TranslatorAbstract;

/**
 * Main Translation method abstract
 * Any method of translation must extends this class
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
abstract class Method extends TranslatorAbstract implements interfaces\Method
{
    /**
     * {@inheritdoc}
     * */
    public string $name = 'method';

    /**
     * @var bool
     */
    public bool $cache_result = true;

    /**
     * @var bool
     */
    public bool $save_translations = true;

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\TranslationDataMapper
     * */
    public string|\NovemBit\i18n\component\translation\models\TranslationDataMapper $model_class = models\Method::class;

    /**
     * @return int
     */
    public function getDbId(): int
    {
        return 0;
    }
}
