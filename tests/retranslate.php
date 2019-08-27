<?php

include_once '../autoload.php';
$texts = [
    "fr-Why you do that",
    "fr-Goodbye sir, see you tomorrow"

];
echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( "fr" )->text->reTranslate(
	$texts
) );

echo "</textarea>";

