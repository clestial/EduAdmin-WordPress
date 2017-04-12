<?php
error_reporting( E_ALL );
include_once( 'edu.api.functions.php' );
include_once( __DIR__ . '/../includes/_apiFunctions.php' );
include_once( __DIR__ . '/../includes/_textFunctions.php' );
if ( session_status() != PHP_SESSION_DISABLED ) {
	if ( ! session_id() ) {
		session_start();
	}
}
date_default_timezone_set( 'UTC' );

$modules = scandir( __DIR__ . '/modules' );
foreach ( $modules as $module ) {
	if ( false !== strpos( $module, '.php' ) ) {
		if ( function_exists( 'opcache_get_status' ) ) {
			$opcachestatus = opcache_get_status();
			if ( $opcachestatus[ "opcache_enabled" ] ) {
				if ( function_exists( 'opcache_compile_file' ) && function_exists( 'opcache_is_script_cached' ) ) {
					if ( ! opcache_is_script_cached( __DIR__ . '/modules/' . $module ) ) {
						opcache_compile_file( __DIR__ . '/modules/' . $module );
					}
				}
			}
		}
		include_once( __DIR__ . '/modules/' . $module );
	}
}