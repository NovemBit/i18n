<?php


namespace NovemBit\i18n\component\translation\type;


use InvalidArgumentException;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\type\interfaces\TypeInterface;
use Psr\Container\ContainerInterface;

class TypeTranslatorFactory
{

    public function __construct(private ContainerInterface $container)
    {
    }

    private array $types = [
        'html'          => HtmlTranslator::class,
        'html_fragment' => HtmlFragmentTranslator::class,
        'json'          => JsonTranslator::class,
        'text'          => TextTranslator::class,
        'url'           => UrlTranslator::class,
        'xml'           => XmlTranslator::class
    ];

    /**
     * @throws TranslationException
     */
    public function getTypeTranslator(string $key): TypeInterface
    {
        if ( ! isset($this->types[$key])) {
            throw new TranslationException(sprintf('Type-translator with key "%s" not registered.', $key));
        }

        return $this->container->get($this->types[$key]);
    }

    public function registerType(string $key, string $allowed_type): self
    {
        $interfaces = class_implements($allowed_type);

        if ( ! isset($interfaces[TypeInterface::class])) {
            throw new InvalidArgumentException($allowed_type . ' is not implementing ' . TypeInterface::class);
        }

        $this->types[$key] = $allowed_type;

        return $this;
    }
}