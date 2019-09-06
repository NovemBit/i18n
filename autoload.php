<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
function e($number, $msg, $file, $line, $vars) {
    print_r(debug_backtrace());
    die();
}
set_error_handler('e');*/

try {

	include_once "vendor/autoload.php";

	spl_autoload_register( function ( $className ) {

		if (strpos($className, "NovemBit\i18n") === 0) {

			$className = str_replace("NovemBit\i18n",'src',$className);

			$className = str_replace( "\\", DIRECTORY_SEPARATOR, $className );

			include_once( __DIR__ . "/$className.php" );
		}

	} );

} catch ( Exception $e ) {
	throw new Exception( 'Cannot include i18n php file.' );
}