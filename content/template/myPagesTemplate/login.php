<?php
ob_start();
global $wp_query;
$q      = $wp_query->query;
$apiKey = get_option( 'eduadmin-api-key' );

if ( isset( $_SESSION['eduadmin-loginUser'] ) && isset( $_SESSION['eduadmin-loginUser']->Contact ) && isset( $_SESSION['eduadmin-loginUser']->Contact->CustomerContactID ) && $_SESSION['eduadmin-loginUser']->Contact->CustomerContactID != 0 ) {
	if ( isset( $q['edu-login'] ) || isset( $q['edu-profile'] ) ) {
		include_once( "profile.php" );
	} else if ( isset( $q['edu-bookings'] ) ) {
		include_once( "bookings.php" );
	} else if ( isset( $q['edu-limiteddiscount'] ) ) {
		include_once( "limitedDiscount.php" );
	} else if ( isset( $q['edu-certificates'] ) ) {
		include_once( "certificates.php" );
	} else if ( isset( $q['edu-password'] ) ) {
		include_once( "changePassword.php" );
	}
} else {
	if ( isset( $q['edu-login'] ) ) {
		include_once( "loginPage.php" );
	} else {
		include_once( "loginPage.php" );
	}
}

$out = ob_get_clean();
return $out;