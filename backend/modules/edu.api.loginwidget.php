<?php
date_default_timezone_set( 'UTC' );
if ( ! function_exists( 'edu_api_loginwidget' ) ) {
	function edu_api_loginwidget( $request ) {
		header( "Content-type: text/html; charset=UTF-8" );
		$surl = $request['baseUrl'];
		$cat  = $request['courseFolder'];

		$baseUrl = $surl . '/' . $cat;
		if ( isset( $_COOKIE['eduadmin-loginUser'] ) ) {
			$user    = $_COOKIE['eduadmin-loginUser'];
			$c2      = json_encode( $user->Contact );
			$contact = json_decode( $c2 );
		}

		if ( isset( $_COOKIE['eduadmin-loginUser'] ) &&
		     ! empty( $_COOKIE['eduadmin-loginUser'] ) &&
		     isset( $contact ) &&
		     isset( $contact->CustomerContactID ) &&
		     $contact->CustomerContactID != 0
		) {
			return
				"<div class=\"eduadminLogin\"><a href=\"" . $baseUrl . "/profile/myprofile" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminMyProfileLink\">" .
				$contact->ContactName .
				"</a> - <a href=\"" . $baseUrl . "/profile/logout" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminLogoutButton\">" .
				$request['logouttext'] .
				"</a>" .
				"</div>";
		} else {
			return
				"<div class=\"eduadminLogin\"><i>" .
				$request['guesttext'] .
				"</i> - " .
				"<a href=\"" . $baseUrl . "/profile/login" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminLoginButton\">" .
				$request['logintext'] .
				"</a>" .
				"</div>";
		}
	}
}

if ( isset( $_REQUEST['module'] ) && $_REQUEST['module'] == "login_widget" ) {
	echo edu_api_loginwidget( $_REQUEST );
}