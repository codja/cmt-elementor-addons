<?php

namespace ElementorCmAddons;

// find any classes from your code
use ElementorCmAddons\classes\ACF;
use ElementorCmAddons\classes\ajax\Ajax;
use ElementorCmAddons\classes\routes\Routes;

spl_autoload_register(
	function ( $class ) {
		$class       = str_replace( __NAMESPACE__, 'includes', $class );
		$parse_class = explode( '\\', $class );

		switch ( true ) {
			case in_array( 'traits', $parse_class, true ):
				$type = 'trait';
				break;
			case in_array( 'interfaces', $parse_class, true ):
				$type = 'interface';
				break;
			default:
				$type = 'class';
		}

		$file_class_name = $type . '-' . strtolower( str_replace( '_', '-', array_pop( $parse_class ) ) ) . '.php';
		$class           = implode( DIRECTORY_SEPARATOR, $parse_class ) . DIRECTORY_SEPARATOR . $file_class_name;
		$path            = ELEMENTOR_CM_ADDONS_PATH . DIRECTORY_SEPARATOR . $class;

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
);

if ( function_exists( '__autoload' ) ) {
	spl_autoload_register( '__autoload' );
}

// ... and call
new Ajax();
new Routes();
new ACF();

