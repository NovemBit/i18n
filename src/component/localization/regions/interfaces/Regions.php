<?php

namespace NovemBit\i18n\component\localization\regions\interfaces;

use NovemBit\i18n\component\localization\interfaces\LocalizationType;

interface Regions extends LocalizationType
{
    public function getConfig(?string $base_domain = null, ?string $value = null);
}
