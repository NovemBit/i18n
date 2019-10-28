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

use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\Translation;
use NovemBit\i18n\system\helpers\DataType;

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
     * @return string|null
     */
    private function _getFieldType(string $value, string $route): ?string
    {
        $type = $this->_getFieldTypeByRoute($route);

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
    private function _getFieldTypeAutomatically(string $str): ?string
    {
        return DataType::getType($str);
    }

    /**
     * Recursive array walk with callback and route
     *
     * @param array    $arr       Main array
     * @param callable $callback  Callback function with 3 params (key/val/route)
     * @param string   $route     Parent route
     * @param string   $separator Route separator
     *
     * @return void
     */
    private static function _arrayWalkWithRoute(
        array &$arr,
        callable $callback,
        string $route = '',
        string $separator = '>'
    ): void {
        foreach ($arr as $key => &$val) {
            $_route = $route == '' ? $key : $route . $separator . $key;
            if (is_array($val)) {
                self::_arrayWalkWithRoute($val, $callback, $_route, $separator);
            } else {
                $callback($key, $val, $_route);
            }
        }
    }

    /**
     * Get field type by route
     *
     * @param string $route Route of object element
     *
     * @return string|null
     */
    private function _getFieldTypeByRoute(string $route): ?string
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
     * @throws TranslationException
     */
    public function doTranslate(array $jsons): array
    {
        $languages = $this->context->getLanguages();

        $to_translate = [];
        $translations = [];
        $result = [];

        foreach ($jsons as $json) {
            $object = $this->_objects[$json];
            self::_arrayWalkWithRoute(
                $object,
                function ($key, &$val, $route) use (&$to_translate) {
                    $type = $this->_getFieldType($val, $route);

                    if ($type !== null) {
                        $to_translate[$type][] = $val;
                    }

                }
            );

        }

        foreach ($to_translate as $type => $values) {
            $translations[$type] = $this->context->{$type}->translate($values);
        }

        foreach ($jsons as &$json) {
            foreach ($languages as $language) {
                $object = $this->_objects[$json];

                self::_arrayWalkWithRoute(
                    $object,
                    function ($key, &$val, $route) use ($translations, $language) {

                        $type = $this->_getFieldType($val, $route);

                        if ($type !== null) {
                            $val = isset($translations[$type][$val][$language])
                                ? $translations[$type][$val][$language] :
                                $val;
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
    public function validateBeforeTranslate(&$json): bool
    {
        $obj = json_decode($json, true);
        if (is_array($obj)) {
            $this->_objects[$json] = $obj;
            return true;
        }
        return false;
    }
}