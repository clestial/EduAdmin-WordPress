<?php
if ( ! function_exists( 'edu_isOfficialPlugin' ) ) {
	function edu_isOfficialPlugin() {
		if ( isset( $_REQUEST['checkOfficialPlugin'] ) ) {
			echo "<script>(function() { alert(new Date() + \"\\nI'm official!\\nVersion: " . EDU()->get_plugin_version() . "\"); })();</script>";
		}
	}
}

if ( ! function_exists( 'edu_check_for_updates' ) ) {
	function edu_check_for_updates() {
		require_once( "includes/auto_update.php" );
		$current_version = EDU()->get_plugin_version();
		$slug            = "eduadmin/eduadmin.php";
		new wp_auto_update( $current_version, $slug );
	}
}
add_action( 'admin_init', 'edu_check_for_updates' );
add_action( 'wp_footer', 'edu_isOfficialPlugin' );
