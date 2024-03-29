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

use Doctrine\DBAL\ConnectionException;
use JsonException;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\component\translation\interfaces\Translator;
use NovemBit\i18n\component\translation\models\TranslationDataMapper;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\Arrays;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

use function json_encode;

/**
 * JSON type for translation component
 *
 * @category Component\Translation\Type
 * @package  Component\Translation\Type
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class JsonTranslator extends TypeAbstract implements interfaces\JSON
{
    /**
     * {@inheritdoc}
     * */
    public string $name = 'json';

    /**
     * @var string
     */
    public string $model = models\JSON::class;

    /**
     * {@inheritdoc}
     * */
    public bool $validation = true;

    /**
     * Model class name of ActiveRecord
     * */
    public string|\NovemBit\i18n\component\translation\models\TranslationDataMapper $model_class = models\JSON::class;

    /**
     * Detect property type automatically
     *
     * @var bool
     * */
    public bool $type_autodetect = true;

    /**
     * Fields to translate
     *
     * @var array
     * */
    public array $fields_to_translate = [];

    /**
     * To translate objects
     *
     * @var array
     * */
    private array $objects = [];

    public function __construct(
        CacheInterface $cache,
        TranslationDataMapper $translation_data_mapper,
        private TypeTranslatorFactory $type_factory,
        Translation $translation
    )
    {

        parent::__construct($cache, $translation, $translation_data_mapper);
    }

    /**
     * Get field type
     *
     * @param  string|null  $value  Value of field
     * @param  string|null  $route  Route of field
     *
     * @return string|callable|null
     */
    private function getFieldType(?string $value, ?string $route): callable|string|null
    {
        $type = null;

        if ($route !== null) {
            $type = $this->getFieldTypeByRoute($route);
        }

        if ($this->type_autodetect === true && $type === null) {
            $type = $this->getFieldTypeAutomatically($value);
        }

        return $type;
    }

    /**
     * Get type of string
     *
     * @param  string|null  $str  String content
     *
     * @return string|null
     */
    private function getFieldTypeAutomatically(?string $str): ?string
    {
        if ($str === null) {
            return null;
        }

        return DataType::getType($str);
    }

    /**
     * Get field type by route
     *
     * @param  string  $route  Route of object element
     *
     * @return string|callable|null
     */
    private function getFieldTypeByRoute(string $route): callable|string|null
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
     * @param  array  $nodes  Jsons string array
     *
     * @param  string  $from_language
     * @param  array  $to_languages
     * @param  bool  $ignore_cache
     *
     * @return array
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    protected function doTranslate(
        array $nodes,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        $to_translate = [];
        $translations = [];
        $result       = [];

        foreach ($nodes as $json) {
            Arrays::arrayWalkWithRoute(
                $this->objects[$json],
                function ($key, &$val, string $route) use (&$to_translate): void {
                    $maybe_html_value = html_entity_decode(
                        $val,
                        ENT_NOQUOTES,
                        'UTF-8'
                    );

                    $type = $this->getFieldType($maybe_html_value, $route);

                    if (is_string($type)) {
                        if (in_array($type, ['html', 'html_fragment'])) {
                            $val = $maybe_html_value;
                        }

                        $to_translate[$type][] = $val;
                    }
                }
            );
        }

        foreach ($to_translate as $type => $values) {
            /** @var Translator $translator */
            $translator = $this->type_factory->getTypeTranslator($type);
            $translator->setHelperAttributes($this->getHelperAttributes());

            $translations[$type] = $translator->translate(
                $values,
                $from_language,
                $to_languages,
                $verbose,
                false,
                $ignore_cache
            );
        }

        foreach ($this->objects as $json => &$object) {
            foreach ($to_languages as $language) {
                Arrays::arrayWalkWithRoute(
                    $object,
                    function ($key, &$val, $route) use ($translations, $language) {
                        $type = $this->getFieldType($val, $route);

                        if ($type !== null) {
                            if (is_string($type)) {
                                $val = $translations[$type][$val][$language] ?? $val;
                            } elseif (is_callable($type)) {
                                $val = $type($val, $language);
                            }
                            $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
                        }
                    }
                );

                $result[$json][$language] = json_encode($object, JSON_THROW_ON_ERROR);
            }
        }

        return $result;
    }

    /**
     * Validate json string before translate
     *
     * @param  string  $text  Json string
     *
     * @return bool
     * @throws JsonException
     */
    protected function validateBeforeTranslate(string &$text): bool
    {
        $obj = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($obj)) {
            $this->objects[$text] = $obj;
            return parent::validateBeforeTranslate($text);
        }

        return false;
    }

    public function getDbId(): int
    {
        return 4;
    }
}
