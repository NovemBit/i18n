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

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use NovemBit\i18n\models\DataMapper;
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
class Translation extends DataMapper implements interfaces\Translation
{
    const TYPE = 0;

    const TABLE = "i18n_translations";

    /**
     * Main method to get translations from DB
     *
     * @param array  $texts         Texts array to translate
     * @param string $from_language From language
     * @param array  $to_languages  To languages list
     * @param bool   $reverse       Use translate column as source (ReTranslate)
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
            }
        }

        $hashes = [];

        foreach ($texts as $text) {
            $hashes[] = self::createHash($text);
        }

        $queryBuilder = self::getDB()->createQueryBuilder();
        $queryBuilder->select('id', 'source', 'to_language', 'translate', 'level')
            ->from(static::TABLE)
            ->where('type = :type')
            ->andWhere('from_language = :from_language')
            ->andWhere('to_language IN (:to_language)')
            ->andWhere(
                ($reverse ? 'translate_hash' : 'source_hash') . ' IN (:hashes)'
            )
            ->addOrderBy('level', 'DESC')
            ->addOrderBy('id', 'ASC')
            ->setParameter('type', static::TYPE)
            ->setParameter('from_language', $from_language)
            ->setParameter('to_language', $to_languages, Connection::PARAM_STR_ARRAY)
            ->setParameter('hashes', $hashes, Connection::PARAM_STR_ARRAY);

        $db_result = $queryBuilder->execute()->fetchAll();

        /*$stmt = self::getDB()->executeCacheQuery(
            $queryBuilder->getSQL(),
            $queryBuilder->getParameters(),
            $queryBuilder->getParameterTypes(),
            new QueryCacheProfile(10000, self::TYPE . '_type')
        );

        $db_result = $stmt->fetchAll();

        $stmt->closeCursor(); // at this point the result is cached*/

        $result = array_merge($result, $db_result);


        return $result;
    }

    private static function createHash($str)
    {
        return md5($str);
    }

    /**
     * @param  string $from_language
     * @param  array  $translations
     * @param  int    $level
     * @param  bool   $overwrite
     * @param  array  $result
     * @throws ConnectionException
     */
    public static function saveTranslations(
        $from_language,
        $translations,
        $level = 0,
        $overwrite = false,
        &$result = []
    ) {

        self::getDB()->beginTransaction();


        foreach ($translations as $source => $haystack) {
            $source_hash = self::createHash($source);
            foreach ($haystack as $to_language => $translate) {

                if ($from_language == $to_language) {
                    continue;
                }

                $translate_hash =  self::createHash($translate);

                $query = self::getDB()->createQueryBuilder();

                try {
                    $query->insert(static::TABLE)->values(
                        [
                            'from_language' => ':from_language',
                            'to_language' => ':to_language',
                            'source' => ':source',
                            'level' => ':level',
                            'type' => ':type',
                            'translate' => ':translate',
                            'source_hash' => ':source_hash',
                            'translate_hash' => ':translate_hash',
                        ]
                    )
                        ->setParameter('from_language', $from_language)
                        ->setParameter('to_language', $to_language)
                        ->setParameter('source', $source)
                        ->setParameter('level', $level)
                        ->setParameter('type', static::TYPE)
                        ->setParameter('translate', $translate)
                        ->setParameter('source_hash', $source_hash)
                        ->setParameter('translate_hash', $translate_hash)
                        ->execute();
                } catch (UniqueConstraintViolationException $exception) {

                    if ($overwrite === true) {
                        $query = self::getDB()->createQueryBuilder();
                        try {
                            $query->update(static::TABLE)
                                ->set('translate', ':translate')
                                ->set('translate_hash', ':translate_hash')

                                ->setParameter('translate', $translate)
                                ->setParameter('translate_hash', $translate_hash)

                                ->where('type = :type')
                                ->andWhere('from_language = :from_language')
                                ->andWhere('to_language IN (:to_language)')
                                ->andWhere('level = :level')
                                ->andWhere('source_hash = :source_hash')
                                ->addOrderBy('id', 'DESC')
                                ->addOrderBy('level', 'ASC')

                                ->setParameter('type', static::TYPE)
                                ->setParameter('from_language', $from_language)
                                ->setParameter('to_language', $to_language)
                                ->setParameter('level', $level)
                                ->setParameter('source_hash', $source_hash)
                                ->setFirstResult(1)
                                ->setMaxResults(1)
                                ->execute();
                        } catch (ConstraintViolationException $exception){

                            $result['errors'][] = $exception->getMessage();
                        }
                    } else {
                        $result['errors'][] = $exception->getMessage();
                    }

                }
            }
        }

        self::getDB()->commit();

        //        try {
        //
        //            $query->fl();
        //
        //        } catch (\Exception $exception){
        //            Module::instance()->log->logger()->warning($exception->getMessage());
        //        }
    }
}
