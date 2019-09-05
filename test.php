<?php

include __DIR__."/autoload.php";

$i18n_config = include 'config.php';

$i18n_config['test'] = [
    'class' => \NovemBit\i18n\test\Test::class,
    'url'   => [
        'class' => \NovemBit\i18n\test\URL::class
    ],
    'language'   => [
        'class' => \NovemBit\i18n\test\Language::class
    ],
    'html'   => [
        'class' => \NovemBit\i18n\test\HTML::class
    ],
    'html_parser'   => [
        'class' => \NovemBit\i18n\test\HTMLParser::class
    ],
    'text'   => [
        'class' => \NovemBit\i18n\test\Text::class
    ],
    'json'   => [
        'class' => \NovemBit\i18n\test\JSON::class
    ]
];

$i18n = new NovemBit\i18n\Module($i18n_config);

$i18n->test->start();
