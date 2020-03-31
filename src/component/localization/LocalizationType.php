<?php

namespace NovemBit\i18n\component\localization;

use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

/**
 * Class LocalizationType
 * @package NovemBit\i18n\component\localization
 */
abstract class LocalizationType extends Component implements interfaces\LocalizationType
{
    public $all;

    /**
     * @param string $key
     * @param string $by
     * @param string|null $return
     * @param bool $all
     *
     * @return mixed|null
     */
    public function getByPrimary(
        string $key,
        string $by,
        ?string $return = null,
        bool $all = false
    ) {
        return call_user_func_array(
            [Arrays::class, $all ? 'ufindAll' : 'ufind'],
            [
                $this->all,
                $key,
                $by,
                $return,
                function ($key, $value) {
                    if (is_string($value) && $key === $value) {
                        return true;
                    } elseif (is_array($value) && isset($value[0]) && $value[0] === $key) {
                        return true;
                    }
                    return false;
                }
            ]
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
        return call_user_func_array(
            [Arrays::class, $all ? 'ufindAll' : 'ufind'],
            [
                $this->all,
                $key,
                $by,
                $return,
                function ($key, $value) {
                    if (is_string($value) && $key === $value) {
                        return true;
                    } elseif (is_array($value) && in_array($key, $value)) {
                        return true;
                    }
                    return false;
                }
            ]
        );
    }
}
