<?php
	date_default_timezone_set( 'UTC' );
	include_once( __DIR__ . "/../../includes/loApiClient.php" );
	include_once( __DIR__ . "/../../includes/loApiClasses.php" );
	$eduapi = new EduAdminClient();

	if ( isset( $_REQUEST['authenticate'] ) && isset( $_REQUEST['key'] ) ) {
		if ( empty( $_REQUEST['key'] ) ) {
			return;
		}
		$info = DecryptApiKey( $_REQUEST['key'] );

		$token = "";

		if ( ! isset( $_COOKIE['edu-usertoken'] ) ) {
			$token = $eduapi->GetAuthToken( $info->UserId, $info->Hash );
			setcookie( 'edu-usertoken', $token );
		} else {
			$valid = $eduapi->ValidateAuthToken( $_COOKIE['edu-usertoken'] );
			if ( ! $valid ) {
				$token = $eduapi->GetAuthToken( $info->UserId, $info->Hash );
				setcookie( 'edu-usertoken', $token );
			}
		}
		setcookie( 'edu-usertoken', $token, time() - 3600 );
		echo edu_encrypt( 'edu_js_token_crypto', $token );
	}