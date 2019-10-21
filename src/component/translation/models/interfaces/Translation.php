<?php


namespace NovemBit\i18n\component\translation\models\interfaces;


interface Translation
{

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
    ) : array;

    /**
     * Main method to save translations in DB
     *
     * @param string $from_language From language
     * @param array  $translations  Translations of texts
     * @param int    $level         Level of translation
     * @param bool   $overwrite     Overwrite or not
     *
     * @return void
     */
    public static function saveTranslations(
        $from_language,
        $translations,
        $level = 0,
        $overwrite = false
    );

}