<?php

include_once '../autoload.php';
$texts = [
	'Hello',
	'Hello sir',
	'Goodbye sir, see you tomorrow',
	"Why you do that",

];
echo "<textarea cols='100' rows='20'>";

var_dump( $i18n->translation->setLanguages( [ 'fr', 'de' ] )->text->translate(
	$texts
) );

echo "</textarea>";

