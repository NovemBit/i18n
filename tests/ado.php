<?php

use i18n\component\translation\models\Translation;

include_once '../autoload.php';
echo "<textarea cols='200' rows='50'>";


//$model = Translation::findByPk(1);
//
//
//$model = new Translation();
//$model->source = "Jan axper";
//$model->translate = "Hi Moto jan";
//$model->level = "999";
//
//
//$model->save();

//$model = Translation::findByPk(18);
//var_dump($model->getFields());

$models = Translation::find( null,null,'10');
var_dump($models);


echo "</textarea>";