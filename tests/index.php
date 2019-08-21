<?php

include_once '../autoload.php';

echo $_SERVER['REQUEST_URI']."<br>";
echo $_SERVER['SCRIPT_NAME']."<br>";


$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];


if (strpos($request_uri, $script_name) === 0) {
   $str = $script_name;
} else{
    $paths = explode('/',$_SERVER['SCRIPT_NAME']);

    unset($paths[count($paths)-1]);

    $str = implode('/',$paths);
}

echo $str.'<br>';


$dest = str_replace($str,'',$request_uri);

echo $dest;


