<?php
function sendForgottenPassword( $loginValue ) {
	$t    = EDU()->start_timer( __METHOD__ );
	$ccId = 0;

	$loginField = get_option( 'eduadmin-loginField', 'Email' );

	$cc = EDUAPI()->OData->Persons->Search(
		null,
		"$loginField eq '" . sanitize_text_field( $loginValue ) . "' and CanLogin"
	);

	if ( $cc["@odata.count"] == 1 ) {
		$ccId = current( $cc["value"] )["PersonId"];
	}

	if ( $ccId > 0 && ! empty( current( $cc["value"] )["Email"] ) ) {
		$sent = EDUAPI()->REST->Person->SendResetPasswordEmailById( $ccId );
		EDU()->stop_timer( $t );
		EDU()->__writeDebug( $sent );

		return $sent["EmailSent"];
	}
	EDU()->stop_timer( $t );

	return false;
}

function logoutUser() {
	$t    = EDU()->start_timer( __METHOD__ );
	$surl = get_home_url();
	$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

	$baseUrl = $surl . '/' . $cat;

	unset( EDU()->session['eduadmin-loginUser'] );
	unset( EDU()->session['needsLogin'] );
	unset( EDU()->session['checkEmail'] );
	EDU()->session->regenerate_id( true );
	unset( $_COOKIE['eduadmin-loginUser'] );
	setcookie( 'eduadmin_loginUser', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
	wp_redirect( $baseUrl . edu_getQueryString() );
	EDU()->stop_timer( $t );
	exit();
}

add_action(
	'wp_loaded',
	function() {
		$api_key = get_option( 'eduadmin-api-key' );

		if ( ! $api_key || empty( $api_key ) ) {
			add_action( 'admin_notices', array( 'EduAdmin', 'setup_warning' ) );
		} else {
			$key = edu_decrypt_api_key( $api_key );
			if ( ! $key ) {
				add_action( 'admin_notices', array( 'EduAdmin', 'setup_warning' ) );

				return;
			}

			$cat = get_option( 'eduadmin-rewriteBaseUrl' );

			if ( stristr( $_SERVER['REQUEST_URI'], "/$cat/profile/logout" ) !== false ) {
				logoutUser();
			}

			/* BACKEND FUNCTIONS FOR FORMS */
			if ( isset( $_POST['eduformloginaction'] ) ) {
				$act = sanitize_text_field( $_POST['eduformloginaction'] );
				if ( isset( $_POST['eduadminloginEmail'] ) ) {
					switch ( $act ) {
						case "forgot":
							$success                                  = sendForgottenPassword( $_POST['eduadminloginEmail'] );
							EDU()->session['eduadmin-forgotPassSent'] = $success;
							break;
					}
				} else {
					EDU()->session['eduadminLoginError'] = __( "You have to provide your login credentials.", 'eduadmin-booking' );
				}
			}
		}
	} );