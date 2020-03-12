<?php

namespace NovemBit\i18n\component\localization;

use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

abstract class LocalizationType extends Component
{
    public $all;

    /**
     * @param string $key
     * @param string $by
     * @param string|null $return
     *
     * @return mixed|null
     */
    public function get(
        string $key,
        string $by,
        ?string $return = null
    ) {

        return Arrays::ufind($this->all, $key, $by, $return, function ($key, $value) {
            if (is_string($value) && $key == $value) {
                return true;
            } elseif (is_array($value) && in_array($key, $value)) {
                return true;
            }
            return false;
        });
    }
}
