<?php


namespace NovemBit\i18n\system\parsers\interfaces;


use DOMElement;

interface Rule
{
    /**
     * Check if tagName|attribute|text
     * exists in array
     *
     * @var string
     * */
    const IN = 'in';

    /**
     * Check tagName|attribute|text regex validation
     *
     * @var string
     * */
    const REGEX = 'regex';

    /**
     * Check equality for tagName|attribute|text
     *
     * @var string
     * */
    const EQ = 'EQ';

    /**
     * Check tagName|attribute|text contains
     *
     * @var string
     * */
    const CONTAINS = 'contains';

    public function __construct(
        array $tags = null,
        array $attrs = null,
        array $texts = null,
        $mode = self::IN
    );

    public function validate(DOMElement $node): bool;
}