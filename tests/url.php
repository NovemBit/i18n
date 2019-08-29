<?php

include_once '../autoload.php';

$urls = [
    'https://wp.me/',
//    'https://wp.me/wp-admin/post.php?post=1&action=edit',
//    '/tests/i18n/tests/url.php/text-1',
    '/i18n/tests/url.php/text-1',
    '/letter-sequence.php',
    "https://wordpress.org/",
    "https://wordpress.org",
    "https://wp.me/wp-admin/",
    "https://wp.me/wp-admin/asd/asda",
    "https://wp.me/sample-page/",
    'https://wp.me/i18n/tests/url.php/text-1',
    'https://wp.me/alala/i18n/tests/url.php/text-1',
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

