<?php

namespace NovemBit\i18n\component\translation\models;


use NovemBit\ActiveData;
use NovemBit\i18n\Module;
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
class TranslationNode extends ActiveData {

    public static $table_name = "bli18n_translations";

    public static function pdo() {
        Module::instance()->db
			->pdo()
			->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		return Module::instance()->db->pdo();
	}

    /**
     * @param      $type
     * @param      $texts
     * @param      $from_language
     * @param      $to_languages
     *
     * @param bool $by_source
     *
     * @return array|bool
     * @throws \Exception
     */
	public static function findTranslations(
		$type,
	    $texts,
		$from_language,
		$to_languages,
        $by_source = true
	) {

	    $result = [];
		$texts        = array_values( $texts );
		$to_languages = array_values( $to_languages );

		if ( empty( $to_languages ) || empty( $texts ) ) {
			return [];
		}

        $texts = array_chunk($texts, 500);
		foreach($texts as $chunk){
            $condition = self::_condition($type,
                $chunk,
                $from_language,
                $to_languages,
                $by_source
            );

            $result = array_merge($result, self::findFields(['source','to_language','translate'], $condition,null,9999 ));
        }
		return $result;
	}


    /**
     * @param      $type
     * @param      $texts
     * @param      $from_language
     * @param      $to_languages
     * @param bool $by_source
     *
     * @return array
     */
    private static function _condition(
	    $type,
        $texts,
        $from_language,
        $to_languages,
        $by_source = true
    ){
        $where = 'type = :type AND from_language = :from_language AND (';

        $params = [ ':type' => $type,':from_language' => $from_language ];

        foreach ( $texts as $text_key => $text ) {

            $text_param = "text_" . $text_key;

            if($by_source) {
                $where .= " ( source = :" . $text_param;
            } else{
                $where .= " ( translate = :" . $text_param;
            }

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

            if ( ! isset( $params[ $text_param ] ) ) {
                $params[ ":".$text_param ] = $text;
            }
        }

        $where.=" )";
        return [$where, $params];
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