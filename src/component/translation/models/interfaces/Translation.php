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
    public function get(
        array $texts,
        string $from_language,
        array $to_languages,
        int $type
    ) : array;

    /**
     * Main method to get re translations from DB
     *
     * @param array  $texts         Texts array to translate
     * @param string $from_language From language
     * @param array  $to_languages  To languages list
     *
     * @return array
     */
    public function getReversed(
        array $texts,
        string $to_language,
        array $from_languages,
        int $type
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
    public function saveTranslations(
        string $from_language,
        array $translations,
        int $type,
        int $level = 0,
        bool $overwrite = false,
        array &$result = []
    );

}