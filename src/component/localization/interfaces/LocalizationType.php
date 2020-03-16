<?php

namespace NovemBit\i18n\component\localization\interfaces;

use NovemBit\i18n\component\localization\countries\interfaces\Countries;
use NovemBit\i18n\component\localization\languages\interfaces\Languages;
use NovemBit\i18n\component\localization\regions\interfaces\Regions;
use NovemBit\i18n\system\interfaces\Component;

/**
 * Languages component interface
 *
 * @property Languages $languages
 * @property Regions $regions
 * @property Countries $countries
 * */
interface LocalizationType extends Component
{
    public function getByPrimary(
        string $key,
        string $by,
        ?string $return = null
    );

    public function getByContains(
        string $key,
        string $by,
        ?string $return = null
    );
}
