<?php


namespace NovemBit\i18n\system\helpers;


class Environment
{

    public static function server(?string $var = null, $val = null){
        if($var == null){
            return $_SERVER ?? null;
        }
        if($val == null){
            return $_SERVER[$var] ?? null;
        } else{
            $_SERVER[$var] = $val;
        }
        return null;
    }

    public static function get(?string $var = null){
        if($var == null){
            return $_GET ?? null;
        }
        return $_GET[$var] ?? null;
    }

    public static function post(?string $var = null){
        if($var == null){
            return $_POST ?? null;
        }
        return $_POST[$var] ?? null;
    }





}