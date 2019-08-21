<?php

include_once '../autoload.php';

$urls = [
    '/test/test2/test4/test5',
    '/bare/hajox',
    'hello/bye'
];



echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( [ 'fr', 'de' ] )->url->translate(
    $urls
));

echo "</textarea>";

