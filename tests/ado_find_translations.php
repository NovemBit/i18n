<?php

use NovemBit\i18n\component\translation\models\TranslationNode;

include_once '../autoload.php';
echo "<textarea cols='200' rows='50'>";



$texts = [
    'fr-Goodbye sir, see you tomorrow',
];

$models = TranslationNode::findTranslations(1, $texts, 'en', [ 'fr'],false );
var_dump( $models );


die;

$texts = [
	'Hello',
	'Hello sir',
	'Goodbye sir, see you tomorrow',
];

$models = TranslationNode::findTranslations(1, $texts, 'en', [ 'fr', 'de' ] );
var_dump( $models );


echo "</textarea>";