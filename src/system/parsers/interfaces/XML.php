<?php


namespace NovemBit\i18n\system\parsers\interfaces;



interface XML
{
    const XML = 1;
    const HTML = 2;

    public function __construct(
        string $xml,
        string $query,
        int $type = self::XML,
        callable $before_translate_callback = null,
        callable $after_translate_callback = null
    );

    public function load(string $xml): self;

    /**
     * Fetch current DOM document XPATH
     *
     * @param callable $text_callback Callback function for Text Nodes
     * @param callable $attr_callback Callback function for Attr Nodes
     *
     * @return void
     */
    public function fetch(callable $text_callback, callable $attr_callback): void;

    public function save(): string;

    public function addTranslateField(
        Rule $rule,
        string $text = 'text',
        $attrs = []
    );
}