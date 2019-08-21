<?php

include_once '../autoload.php';

$html = "test <p>Hello</p>";


echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( [ 'fr', 'de' ] )->html->translate(
	[ $html ]
));

echo "</textarea>";

