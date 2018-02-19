<?php
ob_start();
global $wp_query;
$q       = $wp_query->query;
$api_key = get_option( 'eduadmin-api-key' );

if ( ! empty( EDU()->session['eduadmin-loginUser'] ) && ! empty( EDU()->session['eduadmin-loginUser']->Contact ) && ! empty( EDU()->session['eduadmin-loginUser']->Contact->PersonId ) && 0 !== EDU()->session['eduadmin-loginUser']->Contact->PersonId ) {
	if ( isset( $q['edu-login'] ) || isset( $q['edu-profile'] ) ) {
		include_once 'profile.php';
	} elseif ( isset( $q['edu-bookings'] ) ) {
		include_once 'bookings.php';
	} elseif ( isset( $q['edu-limiteddiscount'] ) ) {
		include_once 'limitedDiscount.php';
	} elseif ( isset( $q['edu-certificates'] ) ) {
		include_once 'certificates.php';
	} elseif ( isset( $q['edu-password'] ) ) {
		include_once 'changePassword.php';
	}
} else {
	if ( isset( $q['edu-login'] ) ) {
		include_once 'loginPage.php';
	} else {
		include_once 'loginPage.php';
	}
}

$out = ob_get_clean();

return $out;
