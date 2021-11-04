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
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use NovemBit\i18n\component\db\DB;
use NovemBit\i18n\models\DataMapper;

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
class TranslationDataMapper implements interfaces\Translation
{
    public const TABLE = "i18n_translations";

    public function __construct(
        private DB $db
    ){}
    /**
     * Main method to get translations from DB
     *
     * @param  array  $texts  Texts array to translate
     * @param  string  $from_language  From language
     * @param  array  $to_languages  To languages list
     * @param  int  $type
     *
     * @return array
     */
    public function get(
        array $texts,
        string $from_language,
        array $to_languages,
        int $type
    ): array {

        $result = [];
        $texts = array_values($texts);
        $to_languages = array_values($to_languages);

        if (($key = array_search($from_language, $to_languages, true)) !== false) {

            unset($to_languages[$key]);
            foreach ($texts as &$text) {
                $result[] = [
                    'source' => $text,
                    'to_language' => $from_language,
                    'translate' => $text,
                ];
            }
            unset($text);
        }

        $hashes = [];

        foreach ($texts as $text) {
            $hashes[] = self::createHash($text);
        }
        $connection = $this->db->getConnection();

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->select('id', 'source', 'to_language', 'translate', 'level')
            ->from(static::TABLE)
            ->where('type = :type')
            ->andWhere('from_language = :from_language')
            ->andWhere('to_language IN (:to_language)')
            ->andWhere('source_hash IN (:hashes)')
            ->addOrderBy('level', 'DESC')
            ->addOrderBy('id', 'ASC')
            ->setParameter('type', $type)
            ->setParameter('from_language', $from_language)
            ->setParameter('to_language', $to_languages, Connection::PARAM_STR_ARRAY)
            ->setParameter('hashes', $hashes, Connection::PARAM_STR_ARRAY);

        $db_result = $queryBuilder->executeQuery()->fetchAllAssociative();

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


    /**
     * @param $texts
     * @param $to_language
     * @param $from_languages
     *
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function getReversed(
        array $texts,
        string $to_language,
        array $from_languages,
        int $type
    ): array {

        $result = [];
        $texts = array_values($texts);
        $from_languages = array_values($from_languages);

        if (($key = array_search($to_language, $from_languages)) !== false) {

            unset($from_languages[$key]);
            foreach ($texts as &$text) {
                $result[] = [
                    'source' => $text,
                    'to_language' => $to_language,
                    'translate' => $text,
                ];
            }
            unset($text);
        }

        $hashes = [];
        foreach ($texts as $text) {
            $hashes[] = self::createHash($text);
        }

        $connection = $this->db->getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->select('id', 'source', 'to_language', 'translate', 'level')
                     ->from(static::TABLE)
                     ->where('type = :type')
                     ->andWhere('to_language = :to_language')
                     ->andWhere('from_language IN (:from_languages)')
                     ->andWhere( 'translate_hash IN (:hashes)')
                     ->addOrderBy('level', 'DESC')
                     ->addOrderBy('id', 'ASC')
                     ->setParameter('type', $type)
                     ->setParameter('to_language', $to_language)
                     ->setParameter('from_languages', $from_languages, Connection::PARAM_STR_ARRAY)
                     ->setParameter('hashes', $hashes, Connection::PARAM_STR_ARRAY);

        $db_result = $queryBuilder->executeQuery()->fetchAllAssociative();

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
    public function saveTranslations(
        string $from_language,
        array $translations,
        int $type,
        int $level = 0,
        bool $overwrite = false,
        array &$result = []
    ) {

        $connection = $this->db->getConnection();
        $connection->beginTransaction();

        foreach ($translations as $source => $haystack) {
            $source_hash = self::createHash($source);
            foreach ($haystack as $to_language => $translate) {

                if ($from_language == $to_language) {
                    continue;
                }

                $translate_hash =  self::createHash($translate);

                $query = $connection->createQueryBuilder();

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
                        ->setParameter('type', $type)
                        ->setParameter('translate', $translate)
                        ->setParameter('source_hash', $source_hash)
                        ->setParameter('translate_hash', $translate_hash)
                        ->executeQuery();
                } catch (UniqueConstraintViolationException $exception) {

                    if ($overwrite === true) {
                        $query = $connection->createQueryBuilder();
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

                                ->setParameter('type', $type)
                                ->setParameter('from_language', $from_language)
                                ->setParameter('to_language', $to_language)
                                ->setParameter('level', $level)
                                ->setParameter('source_hash', $source_hash)
                                ->setFirstResult(1)
                                ->setMaxResults(1)
                                ->executeQuery();
                        } catch (ConstraintViolationException $exception){

                            $result['errors'][] = $exception->getMessage();
                        }
                    } else {
                        $result['errors'][] = $exception->getMessage();
                    }

                }
            }
        }

        $connection->commit();
    }
}
