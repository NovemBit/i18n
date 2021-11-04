<?php

namespace NovemBit\i18n\component\localization;

use NovemBit\i18n\system\helpers\Arrays;

/**
 * Class LocalizationType
 * @package NovemBit\i18n\component\localization
 */
abstract class LocalizationType implements interfaces\LocalizationType
{
    private array $all;

    /**
     * @param  string  $key
     * @param  string  $by
     * @param  string|null  $return
     * @param  bool  $all
     *
     * @return mixed|null
     */
    public function getByPrimary(
        string $key,
        string $by,
        ?string $return = null,
        bool $all = false
    ) {
        return call_user_func(
            [Arrays::class, $all ? 'ufindAll' : 'ufind'],
            $this->all,
            $key,
            $by,
            $return,
            static function ($key, $value) {
                if (is_string($value) && $key === $value) {
                    return true;
                }

                if (is_array($value) && isset($value[0]) && $value[0] === $key) {
                    return true;
                }
                return false;
            }
        );
    }

    /**
     * @param string $key
     * @param string $by
     * @param string|null $return
     * @param bool $all
     *
     * @return mixed|null
     */
    public function getByContains(
        string $key,
        string $by,
        ?string $return = null,
        bool $all = false
    ) {
        return call_user_func(
            [Arrays::class, $all ? 'ufindAll' : 'ufind'],
            $this->all,
            $key,
            $by,
            $return,
            static function ($key, $value) {
                if (is_string($value) && $key === $value) {
                    return true;
                }

                if (is_array($value) && in_array($key, $value)) {
                    return true;
                }

                return false;
            }
        );
    }

    /**
     * @param  array  $all
     *
     * @return LocalizationType
     */
    public function setAll(array $all): LocalizationType
    {
        $this->all = $all;

        return $this;
    }

    /**
     * @return array
     */
    protected function getAll(): array
    {
        return $this->all;
    }
}
