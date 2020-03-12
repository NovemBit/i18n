<?php

namespace NovemBit\i18n\component\localization\regions\interfaces;

interface Regions
{

    public function getConfig(?string $base_domain = null, ?string $value = null);
}
