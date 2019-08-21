<?php

use NovemBit\i18n\component\translation\models\Translation;

include_once '../autoload.php';
echo "<textarea cols='200' rows='50'>";


$texts = [
	'Hello',
	'Hello sir',
	'Goodbye sir, see you tomorrow',
];

$models = Translation::findTranslations( $texts, 'en', [ 'fr', 'de' ] );
var_dump( $models );


echo "</textarea>";