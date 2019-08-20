<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {

	include_once "vendor/autoload.php";

	spl_autoload_register( function ( $className ) {

		if ( preg_match( '/^BLi18n\\\\.*/', $className ) ) {
			$className = str_replace( "\\", DIRECTORY_SEPARATOR, $className );

			include_once( __DIR__ . "/$className.php" );
		}

	} );

} catch ( Exception $e ) {
	throw new Exception( 'Cannot include BLi18n php file.' );
}

$i18n = new \i18n\Module();