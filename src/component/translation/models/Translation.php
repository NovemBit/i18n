<?php

namespace i18n\component\translation\models;


use i18n\system\ActiveData;
use PDO;

/**
 * @property int id
 * @property string source
 * @property int from_language
 * @property int to_language
 * @property string translate
 * @property int level
 * @property int created_at
 * @property int updated_at
 */
class Translation extends ActiveData {

	public static function pdo() {
		global $i18n;
		$i18n->db->pdo()->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $i18n->db->pdo();
	}

	public static $table_name = "bli18n_translations";
}