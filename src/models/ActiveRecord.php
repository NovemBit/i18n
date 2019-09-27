<?php


namespace NovemBit\i18n\models;


use NovemBit\i18n\Module;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public static function getDb()
    {
        return Module::instance()->db->getConnection();
    }

}