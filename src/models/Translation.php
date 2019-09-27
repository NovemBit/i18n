<?php


namespace NovemBit\i18n\models;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;

/**
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

    public static function tableName()
    {
        return "{{%translations}}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['from_language', 'to_language'], 'required'],

            [
                ['from_language', 'to_language', 'type', 'source', 'level'],
                'unique',
                'targetAttribute' => ['from_language', 'to_language', 'type', 'source', 'level']
            ],

            [
                ['from_language', 'to_language'],
                'unique',
                'targetAttribute' => ['from_language', 'to_language']
            ],

            [['type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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
                    return FlyName::find()->orderBy(new Expression('rand()'))->one()->id;
                },
            ],*/
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public static function get($type, $texts, $from_language, $to_languages, $reverse = false)
    {

        $texts = array_values($texts);
        $to_languages = array_values($to_languages);

        $query = self::find();
        $query
            ->select(['source', 'to_language', 'translate'])->where(['type' => $type])
            ->andWhere(['from_language' => $from_language])
            ->andWhere(['in', 'to_language', $to_languages])
            ->andWhere(['in', ($reverse ? 'translate' : 'source'), $texts]);

        $result = $query->asArray()->all();

        return $result;
    }

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