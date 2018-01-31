<?php
	function sendForgottenPassword( $loginValue ) {
		$t        = EDU()->StartTimer( __METHOD__ );
		$ccId     = 0;

		$loginField = get_option( 'eduadmin-loginField', 'Email' );

		$filter = new XFiltering();
		$f      = new XFilter( $loginField, '=', sanitize_text_field( $loginValue ) );
		$filter->AddItem( $f );
		$f = new XFilter( 'CanLogin', '=', true );
		$filter->AddItem( $f );
		$cc = EDU()->api->GetCustomerContact( EDU()->get_token(), '', $filter->ToString(), false );
		if ( count( $cc ) == 1 ) {
			$ccId = current( $cc )->CustomerContactID;
		}

		if ( $ccId > 0 && ! empty( current( $cc )->Email ) ) {
			$sent = EDU()->api->SendCustomerContactPassword( EDU()->get_token(), $ccId, get_bloginfo( 'name' ) );
			EDU()->StopTimer( $t );

			return $sent;
		}
		EDU()->StopTimer( $t );

		return false;
	}

	function logoutUser() {
		$t    = EDU()->StartTimer( __METHOD__ );
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
		EDU()->StopTimer( $t );
		exit();
	}

	add_action(
		'wp_loaded',
		function() {
			$apiKey = get_option( 'eduadmin-api-key' );

			if ( ! $apiKey || empty( $apiKey ) ) {
				add_action( 'admin_notices', array( 'EduAdmin', 'SetupWarning' ) );
			} else {
				$key = DecryptApiKey( $apiKey );
				if ( ! $key ) {
					add_action( 'admin_notices', array( 'EduAdmin', 'SetupWarning' ) );

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