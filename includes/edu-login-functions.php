<?php
function edu_send_forgotten_password( $login_value ) {
	$t     = EDU()->start_timer( __METHOD__ );
	$cc_id = 0;

	$login_field = get_option( 'eduadmin-loginField', 'Email' );

	$cc = EDUAPI()->OData->Persons->Search(
		null,
		"$login_field eq '" . sanitize_text_field( $login_value ) . '\' and CanLogin',
		null,
		null,
		null,
		null,
		true
	);

	if ( 1 === $cc['@odata.count'] ) {
		$cc_id = current( $cc['value'] )['PersonId'];
	}

	if ( $cc_id > 0 && ! empty( current( $cc['value'] )['Email'] ) ) {
		$sent = EDUAPI()->REST->Person->SendResetPasswordEmailById( $cc_id );
		EDU()->stop_timer( $t );

		return $sent['EmailSent'];
	}
	EDU()->stop_timer( $t );

	return false;
}

function edu_logout_user() {
	$t    = EDU()->start_timer( __METHOD__ );
	$surl = get_home_url();
	$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

	$base_url = $surl . '/' . $cat;

	unset( EDU()->session['eduadmin-loginUser'] );
	unset( EDU()->session['needsLogin'] );
	unset( EDU()->session['checkEmail'] );
	EDU()->session->regenerate_id( true );
	unset( $_COOKIE['eduadmin-loginUser'] );
	wp_redirect( $base_url . edu_get_query_string() );
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

			if ( false !== stristr( $_SERVER['REQUEST_URI'], "/$cat/profile/logout" ) ) {
				edu_logout_user();
			}

			/* BACKEND FUNCTIONS FOR FORMS */
			if ( ! empty( $_POST['edu-login-ver'] ) && wp_verify_nonce( $_POST['edu-login-ver'], 'edu-profile-login' ) && ! empty( $_POST['eduformloginaction'] ) ) {
				$act = sanitize_text_field( $_POST['eduformloginaction'] );
				if ( isset( $_POST['eduadminloginEmail'] ) ) {
					switch ( $act ) {
						case 'forgot':
							$success = edu_send_forgotten_password( $_POST['eduadminloginEmail'] );

							EDU()->session['eduadmin-forgotPassSent'] = $success;
							break;
					}
				} else {
					EDU()->session['eduadminLoginError'] = __( 'You have to provide your login credentials.', 'eduadmin-booking' );
				}
			}
		}
	} );
