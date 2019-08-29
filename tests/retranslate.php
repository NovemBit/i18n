<?php

include_once '../autoload.php';



$texts = [
    "2019-fr/08-fr/27-fr/hello-world-fr/?test=123123&language=en"

];
echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( "fr" )->url->reTranslate(
	$texts
) );

echo "</textarea>";


die;

$texts = [
    "fr-Why you do that",
    "fr-Goodbye sir, see you tomorrow",

];
echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( "fr" )->text->reTranslate(
	$texts
) );

echo "</textarea>";

