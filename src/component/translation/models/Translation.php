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

namespace NovemBit\i18n\component\translation\models;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use NovemBit\i18n\models\ActiveRecord;
use NovemBit\i18n\models\exceptions\ActiveRecordException;
use NovemBit\i18n\Module;
use yii\db\Exception;

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
 * @property string $source
 * @property string $translate
 * @property int $level
 * */
class Translation extends ActiveRecord implements interfaces\Translation
{
    const TYPE = 0;

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
            /**
             * Make unique bundle for multiple columns
             * */
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
            [['from_language', 'to_language'], 'string', 'max' => 2],
            [['type', 'level'], 'integer', 'min' => 0, 'max' => 99],
        ];
    }

    /**
     * Before save set type of node
     *
     * @param bool $insert if insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->type = static::TYPE;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Yii2 component behaviours
     * Using timestamp behaviour
     * To set created and updated at columns values.
     *
     * @return array
     */
    public function behaviors()
    {
        return [

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
     * @param array $texts Texts array to translate
     * @param string $from_language From language
     * @param array $to_languages To languages list
     * @param bool $reverse Use translate column as source (ReTranslate)
     *
     * @return array
     */
    public static function get(
        $texts,
        $from_language,
        $to_languages,
        $reverse = false
    ): array {

        $result = [];
        $texts = array_values($texts);
        $to_languages = array_values($to_languages);

        if (($key = array_search($from_language, $to_languages)) !== false) {
            unset($to_languages[$key]);
            foreach ($texts as &$text) {
                $result[] = [
                    'source' => $text,
                    'to_language' => $from_language,
                    'translate' => $text,
                ];
                $text = md5($text);
            }
        }


        $queryBuilder = self::getDB2()->createQueryBuilder();
        $queryBuilder->select('id', 'source', 'to_language', 'translate', 'level')
            ->from('i18n_translations')
            ->where('type = :type')
            ->andWhere('from_language = :from_language')
            ->andWhere('to_language IN (:to_language)')
            ->andWhere(($reverse ? 'translate_hash' : 'source_hash') . ' IN (:texts)')
            ->orderBy('id', 'DESC')
            ->addOrderBy('level', 'ASC')
            ->setParameter('type', static::TYPE)
            ->setParameter('from_language', $from_language)
            ->setParameter('to_language', $to_languages, Connection::PARAM_STR_ARRAY)
            ->setParameter('texts', $texts, Connection::PARAM_STR_ARRAY);

        $db_result = $queryBuilder->execute()->fetchAll();

//
//        var_dump($result);
//        die;
//
//
//        $query = static::find();
//        $query
//            ->select(['id', 'source', 'to_language', 'translate', 'level'])
//            ->where(['type' => static::TYPE])
//            ->andWhere(['from_language' => $from_language])
//            ->andWhere(['in', 'to_language', $to_languages])
//            ->andWhere(['in', ($reverse ? 'translate' : 'source'), $texts])
//            ->orderBy(['id' => SORT_DESC, 'level' => SORT_ASC]);

        $result = array_merge($result, $db_result);


        return $result;
    }

    /**
     * Main method to save translations in DB
     *
     * @param string $from_language From language
     * @param array $translations Translations of texts
     * @param int $level Level of translation
     * @param bool $overwrite If translation exists, then overwrite value
     * @param array $result Result about saving
     *
     * @return void
     * @throws ActiveRecordException
     */
    public static function saveTranslations3(
        $from_language,
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ) {

        $transaction = static::getDb()->beginTransaction();

        foreach ($translations as $source => $haystack) {

            foreach ($haystack as $to_language => $translate) {

                if ($from_language == $to_language) {
                    continue;
                }

                $model = null;

                if ($overwrite === true) {

                    $model = static::find()
                        ->where(['from_language' => $from_language])
                        ->andWhere(['type' => static::TYPE])
                        ->andWhere(['to_language' => $to_language])
                        ->andWhere(['source' => $source])
                        ->andWhere(['level' => $level])
                        ->one();
                }

                if ($model == null) {
                    $model = new static();
                    $model->from_language = $from_language;
                    $model->to_language = $to_language;
                    $model->source = $source;
                    $model->level = $level;
                    $model->type = static::TYPE;
                }

                $model->translate = $translate;

                if ($model->validate() && $model->save()) {
                    $result['success'][] = $model->id;
                } else {
                    $result['errors'][] = $model->errors;
                }
            }
        }

        try {
            $transaction->commit();
        } catch (Exception $exception) {
            throw new ActiveRecordException($exception->getMessage());
        }
    }


    public static function saveTranslations(
        $from_language,
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ) {

        self::getDB2()->beginTransaction();


        foreach ($translations as $source => $haystack) {

            foreach ($haystack as $to_language => $translate) {

                if ($from_language == $to_language) {
                    continue;
                }

//                $model = null;
//
//                if ($overwrite === true) {
//
//                    $model = static::find()
//                        ->where(['from_language' => $from_language])
//                        ->andWhere(['type' => static::TYPE])
//                        ->andWhere(['to_language' => $to_language])
//                        ->andWhere(['source' => $source])
//                        ->andWhere(['level' => $level])
//                        ->one();
//                }
                $query = self::getDB2()->createQueryBuilder();

                try {
                    $query->insert('i18n_translations')->values([
                        'from_language' => ':from_language',
                        'to_language' => ':to_language',
                        'source' => ':source',
                        'level' => ':level',
                        'type' => ':type',
                        'translate' => ':translate',
                        'source_hash' => ':source_hash',
                        'translate_hash' => ':translate_hash',
                    ])
                        ->setParameter('from_language', $from_language)
                        ->setParameter('to_language', $to_language)
                        ->setParameter('source', $source)
                        ->setParameter('level', $level)
                        ->setParameter('type', static::TYPE)
                        ->setParameter('translate', $translate)
                        ->setParameter('source_hash', md5($source))
                        ->setParameter('translate_hash', md5($translate))
                        ->execute();
                } catch (UniqueConstraintViolationException $exception){
                    Module::instance()->log->logger()->warning($exception->getMessage());
                }
            }
        }

        self::getDB2()->commit();

//        try {
//
//            $query->fl();
//
//        } catch (\Exception $exception){
//            Module::instance()->log->logger()->warning($exception->getMessage());
//        }
    }
}
