<?php

/**
 * Languages component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\localization;

use NovemBit\i18n\component\localization\countries\Countries;
use NovemBit\i18n\component\localization\languages\Languages;
use NovemBit\i18n\component\localization\regions\Regions;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Arrays;

/**
 * @property Module $context
 * @property Languages $languages
 * @property Countries $countries
 * @property Regions $regions
 * */
class Localization extends Component implements interfaces\Localization
{
    /**
     * Default language
     *
     * @var array[string][string]
     * */
    public $localization_config = [];

    /**
     * @return array
     */
    public static function defaultConfig(): array
    {
        return [
            'languages' => ['class' => Languages::class],
            'countries' => ['class' => Countries::class],
            'regions' => ['class' => Regions::class],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $base_domain Base domain
     *
     * @return array
     */
    public function getConfig(?string $base_domain = null, ?string $value = null): array
    {
        $config = [];

        foreach ($this->localization_config as $domain_pattern => $_config) {
            if (preg_match("/$domain_pattern/", $base_domain)) {
                $config = $_config;
                break;
            }
        }

        if (!isset($config) && isset($this->localization_config['default'])) {
            $config = $this->localization_config['default'];
            $config['is_default'] = true;
        }

        if ($value !== null) {
            return $config[$value] ?? null;
        }

        return $config;
    }
}
