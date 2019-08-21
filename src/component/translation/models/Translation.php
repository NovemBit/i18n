<?php

namespace NovemBit\i18n\component\translation\models;


use NovemBit\ActiveData;
use PDO;

/**
 * @property int    id
 * @property string source
 * @property int    from_language
 * @property int    type
 * @property int    to_language
 * @property string translate
 * @property int    level
 * @property int    created_at
 * @property int    updated_at
 */
class Translation extends ActiveData {

    public static $table_name = "bli18n_translations";

    public static function pdo() {
		global $i18n;
		$i18n->db
			->pdo()
			->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		return $i18n->db->pdo();
	}

    /**
     * @param $type
     * @param $texts
     * @param $from_language
     * @param $to_languages
     *
     * @return array|bool
     * @throws \Exception
     */
	public static function findTranslations(
		$type,
	    $texts,
		$from_language,
		$to_languages
	) {

		$texts        = array_values( $texts );
		$to_languages = array_values( $to_languages );

		if ( empty( $to_languages ) || empty( $texts ) ) {
			return [];
		}

		$where = 'type = :type AND from_language = :from_language AND (';

		$params = [ ':type' => $type,':from_language' => $from_language ];

		foreach ( $texts as $text_key => $text ) {

			$source_param = "source_".$text_key;

			$where .= " ( source = :" . $source_param;

			$where .= " AND (";

			foreach ( $to_languages as $language_key => $language ) {
				$language_param =  "language_".$language_key;
				$where          .= " (to_language = :" . $language_param . " )";

				if ( ! isset( $params[ $language_param ] ) ) {
					$params[ ":".$language_param ] = $language;
				}

				if ( $language_key != count( $to_languages ) - 1 ) {
					$where .= " OR";
				}
			}
			$where .= " ) )";

			if ( $text_key != count( $texts ) - 1 ) {
				$where .= " OR";
			}

			if ( ! isset( $params[ $source_param ] ) ) {
				$params[ ":".$source_param ] = $text;
			}
		}

		$where.=" )";

		return self::findFields(['source','to_language','translate'], [$where,$params],null,10 );
	}

	public static function saveTranslations($from_language, $type, $translations){

		self::pdo()->beginTransaction();

		try {
            foreach ($translations as $source => $haystack) {
                foreach ($haystack as $to_language => $translate) {
                    $model                = new self();
                    $model->from_language = $from_language;
                    $model->type          = $type;
                    $model->to_language   = $to_language;
                    $model->source        = $source;
                    $model->translate     = $translate;
                    $model->level         = 0;
                    $model->created_at    = time();
                    $model->save();
                }

            }
        } catch (\Exception $exception){
		    self::pdo()->rollBack();
        }
		self::pdo()->commit();

	}

}