<?php
/**
 * Translations active record model
 * php version 7.2.10
 *
 * @category Models
 * @package  Models
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type\models;

use NovemBit\i18n\component\translation\models\TranslationDataMapper;

/**
 * ActiveRecord class. Child of Yii ActiveRecord library
 *
 * @category Models
 * @package  Models
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 * */
class URL extends TranslationDataMapper
{
    public const TYPE = 2;

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            [
                /**
                 * Make unique bundle for multiple columns
                 * */
                [
                    ['from_language', 'to_language', 'translate', 'level','type'],
                    'unique',
                    'targetAttribute' => [
                        'from_language',
                        'to_language',
                        'translate',
                        'level',
                        'type'
                    ]
                ],
            ],
            parent::rules()
        );
    }
}
