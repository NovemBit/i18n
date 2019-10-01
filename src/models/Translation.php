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

namespace NovemBit\i18n\models;

use yii\behaviors\TimestampBehavior;


/**
 * ActiveRecord class. Child of Yii ActiveRecord library
 *
 * @category Models
 * @package  Models
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property int $id
 * @property string $from_language
 * @property string $to_language
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 * @property string $source
 * @property string $translate
 * @property int $level
 * */
class Translation extends ActiveRecord
{

    /**
     * Table name in DB
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%translations}}";
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function rules()
    {
        return [
            /*[['from_language', 'to_language'], 'required'],*/

            [
                ['from_language', 'to_language', 'type', 'source', 'level'],
                'unique',
                'targetAttribute' => [
                    'from_language',
                    'to_language',
                    'type',
                    'source',
                    'level'
                ]
            ],

            [
                ['from_language', 'to_language'],
                'unique',
                'targetAttribute' => ['from_language', 'to_language']
            ],

            [['type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * Yii component behaviours
     *  Using timestamp behaviour to set created and updated at
     *  Column values.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
            /*,
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'name_id',
                ],
                'value' => function ($event) {
                    return FlyName::find()
                        ->orderBy(new Expression('rand()'))->one()->id;
                },
            ],*/
        ];
    }

    /**
     * Attribute values
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Main method to get translations from DB
     *
     * @param int $type Type of translated string
     * @param array $texts Texts array to translate
     * @param string $from_language From language
     * @param array $to_languages To languages list
     * @param bool $reverse Use translate column as source (ReTranslate)
     *
     * @return array
     */
    public static function get(
        $type,
        $texts,
        $from_language,
        $to_languages,
        $reverse = false
    ) {

        $result = [];
        $texts = array_values($texts);
        $to_languages = array_values($to_languages);

        if (($key = array_search($from_language, $to_languages)) !== false) {
            unset($to_languages[$key]);
            foreach ($texts as $text) {
                $result[] = [
                    'source' => $text,
                    'to_language' => $from_language,
                    'translate' => $text,
                ];
            }
        }

        $query = self::find();
        $query
            ->select(['source', 'to_language', 'translate'])
            ->where(['type' => $type])
            ->andWhere(['from_language' => $from_language])
            ->andWhere(['in', 'to_language', $to_languages])
            ->andWhere(['in', ($reverse ? 'translate' : 'source'), $texts]);

        $result = array_merge($result, $query->asArray()->all());

        return $result;
    }

    /**
     * Main method to save translations in DB
     *
     * @param string $from_language From language
     * @param int $type Type of translations
     * @param array $translations Translations of texts
     *
     * @return void
     */
    public static function saveTranslations($from_language, $type, $translations)
    {

        foreach ($translations as $source => $haystack) {
            foreach ($haystack as $to_language => $translate) {

                if ($from_language == $to_language) {
                    continue;
                }

                $model = new self();
                $model->from_language = $from_language;
                $model->type = $type;
                $model->to_language = $to_language;
                $model->source = $source;
                $model->translate = $translate;
                $model->level = 0;
                $model->created_at = time();

                if ($model->validate()) {
                    $model->save();
                }
            }
        }

    }
}