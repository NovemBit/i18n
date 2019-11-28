<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\type;

use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\interfaces\Translator;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\Arrays;

/**
 * JSON type for translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Translation context
 */
class JSON extends Type
{
    /**
     * {@inheritdoc}
     * */
    public $name = 'json';

    /**
     * {@inheritdoc}
     * */
    public $model = models\JSON::class;

    /**
     * {@inheritdoc}
     * */
    public $validation = true;

    /**
     * Model class name of ActiveRecord
     *
     * @var \NovemBit\i18n\component\translation\models\Translation
     * */
    public $model_class = models\JSON::class;

    /**
     * Detect property type automatically
     *
     * @var bool
     * */
    public $type_autodetect = true;

    /**
     * Fields to translate
     *
     * @var array
     * */
    public $fields_to_translate = [];

    /**
     * To translate objects
     *
     * @var array
     * */
    private $_objects = [];

    /**
     * Get field type
     *
     * @param string $value Value of field
     * @param string $route Route of field
     *
     * @return string|callable|null
     */
    private function _getFieldType(?string $value, ?string $route)
    {
        $type = null;

        if ($route !== null) {
            $type = $this->_getFieldTypeByRoute($route);
        }

        if ($this->type_autodetect === true && $type === null) {
            $type = $this->_getFieldTypeAutomatically($value);
        }

        return $type;
    }

    /**
     * Get type of string
     *
     * @param string $str String content
     *
     * @return string|null
     */
    private function _getFieldTypeAutomatically(?string $str): ?string
    {
        if ($str == null) {
            return null;
        }

        return DataType::getType($str);
    }

    /**
     * Get field type by route
     *
     * @param string $route Route of object element
     *
     * @return string|callable|null
     */
    private function _getFieldTypeByRoute(string $route)
    {
        foreach ($this->fields_to_translate as $pattern => $type) {
            if (preg_match($pattern, $route)) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Doing translate method
     *
     * @param array $jsons Jsons string array
     *
     * @return array
     */
    protected function doTranslate(
        array $jsons,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {

        $to_translate = [];
        $translations = [];
        $result = [];

        foreach ($jsons as $json) {
            Arrays::arrayWalkWithRoute(
                $this->_objects[$json],
                function ($key, &$val, $route) use (&$to_translate) {

                    $maybe_html_value = html_entity_decode(
                        $val,
                        ENT_NOQUOTES,
                        'UTF-8'
                    );

                    $type = $this->_getFieldType($maybe_html_value, $route);

                    if (is_string($type)) {
                        if ($type !== null) {

                            if (in_array($type, ['html', 'html_fragment'])) {
                                $val = $maybe_html_value;
                            }

                            $to_translate[$type][] = $val;
                        }
                    }
                }
            );

        }

        foreach ($to_translate as $type => $values) {

            /** @var Translator $translator */
            $translator = $this->context->{$type};

            $translations[$type] = $translator->translate(
                $values,
                $verbose,
                false,
                $ignore_cache
            );
        }

        foreach ($this->_objects as $json => &$object) {
            foreach ($to_languages as $language) {

                Arrays::arrayWalkWithRoute(
                    $object,
                    function ($key, &$val, $route) use ($translations, $language) {

                        $type = $this->_getFieldType($val, $route);

                        if ($type !== null) {
                            if (is_string($type)) {
                                $val = isset($translations[$type][$val][$language])
                                    ? $translations[$type][$val][$language] :
                                    $val;
                            } elseif (is_callable($type)) {
                                $val = call_user_func($type, $val, $language);
                            }
                        }
                    }
                );
                $result[$json][$language] = json_encode($object);
            }
        }

        return $result;

    }

    /**
     * Validate json string before translate
     *
     * @param string $json Json string
     *
     * @return bool
     */
    protected function validateBeforeTranslate(&$json): bool
    {
        $obj = json_decode($json, true);
        if (is_array($obj)) {
            $this->_objects[$json] = $obj;
            return true;
        }
        return false;
    }
}