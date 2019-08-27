<?php

include_once '../autoload.php';

$urls = [
    '/tests/i18n/tests/url.php/text-1',
    '/letter-sequence.php'
//    'help/bro/#asd',
//    '/#',
//    '/#test',
//    '#test',
//    '/test/test2/test4/test6?test=123123',
//    '/bare/hajox',
//    'hello/bye',
//    'tests/i18n/tests/url.php/test/test1/tqw3'
];

//$urls = [
//
//    '/test/test2/test4/test6',
//
//];

echo "<textarea cols='150' rows='40'>";

var_dump( $i18n->translation->setLanguages( [ 'fr', 'de' ] )->url->translate(
    $urls
));

echo "</textarea>";

