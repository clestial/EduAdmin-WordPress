<?php
	header( "Content-type: application/json; charset=utf-8" );
	error_reporting( E_ALL );
	define( 'DOING_AJAX', 1 );
	define( 'SHORTINIT', 1 );
	// Iew, I don't like this. But since both admin-ajax and REST was slow as.. yeah. Syrup.
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
	require( '../eduadmin.php' );
