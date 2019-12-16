<?php


namespace NovemBit\i18n\component\languages\traits;


trait Languages
{
    /**
     * @param string      $key
     * @param string      $by
     * @param string|null $return
     *
     * @return array|string|null
     */
    private static function _getLanguage(
        string $key,
        string $by = 'alpha1',
        ?string $return = 'name'
    ) {
        $languages = \NovemBit\i18n\system\helpers\Languages::getLanguages();
        foreach ($languages as $language) {
            if (isset($language[$by])
                && is_string($language[$by])
                && $language[$by] == strtolower($key)
            ) {
                if ($return!=null) {
                    return $language[$return] ?? null;
                } else {
                    return $language;
                }
            }
        }
        return null;
    }



}
