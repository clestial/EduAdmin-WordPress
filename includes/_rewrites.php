<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

	function eduadmin_activate_rewrite() {
		EDU()->timers[ __METHOD__ ] = microtime( true );
		eduadmin_rewrite_init();
		flush_rewrite_rules();
		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
	}

	function eduadmin_deactivate_rewrite() {
		EDU()->timers[ __METHOD__ ] = microtime( true );
		flush_rewrite_rules();
		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
	}

	function eduadmin_rewrite_init() {
		EDU()->timers[ __METHOD__ ] = microtime( true );
		add_rewrite_tag( '%courseSlug%', '([^&]+)' );
		add_rewrite_tag( '%courseId%', '([^&]+)' );
		add_rewrite_tag( '%edu-login%', '([^&]+)' );
		add_rewrite_tag( '%edu-profile%', '([^&]+)' );
		add_rewrite_tag( '%edu-bookings%', '([^&]+)' );
		add_rewrite_tag( '%edu-certificates%', '([^&]+)' );
		add_rewrite_tag( '%edu-limiteddiscount%', '([^&]+)' );
		add_rewrite_tag( '%edu-logout%', '([^&]+)' );
		add_rewrite_tag( '%edu-password%', '([^&]+)' );

		$listView    = get_option( 'eduadmin-listViewPage' );
		$loginView   = get_option( 'eduadmin-loginViewPage' );
		$detailsView = get_option( 'eduadmin-detailViewPage' );
		$bookingView = get_option( 'eduadmin-bookingViewPage' );

		$objectInterestPage = get_option( 'eduadmin-interestObjectPage' );
		$eventInterestPage  = get_option( 'eduadmin-interestEventPage' );

		$courseFolder = get_option( 'eduadmin-rewriteBaseUrl' );
		$courseFolder = trim( $courseFolder );
		if ( $courseFolder != false && ! empty( $courseFolder ) ) {
			//if($loginView != false)
			{
				add_rewrite_rule( $courseFolder . '/profile/login/?', 'index.php?page_id=' . $loginView . '&edu-login=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/myprofile/?', 'index.php?page_id=' . $loginView . '&edu-profile=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/bookings/?', 'index.php?page_id=' . $loginView . '&edu-bookings=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/card/?', 'index.php?page_id=' . $loginView . '&edu-limiteddiscount=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/certificates/?', 'index.php?page_id=' . $loginView . '&edu-certificates=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/changepassword/?', 'index.php?page_id=' . $loginView . '&edu-password=1', 'top' );
				add_rewrite_rule( $courseFolder . '/profile/logout/?', 'index.php?page_id=' . $loginView . '&edu-logout=1', 'top' );
			}

			if ( $bookingView != false ) {
				if ( $eventInterestPage != false ) {
					add_rewrite_rule( $courseFolder . '/(.*?)__(.*)/book/interest/?', 'index.php?page_id=' . $eventInterestPage . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
				}
				add_rewrite_rule( $courseFolder . '/(.*?)__(.*)/book/?', 'index.php?page_id=' . $bookingView . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
			}

			if ( $detailsView != false ) {
				if ( $objectInterestPage ) {
					add_rewrite_rule( $courseFolder . '/(.*?)__(.*)/interest/?', 'index.php?page_id=' . $objectInterestPage . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
				}
				add_rewrite_rule( $courseFolder . '/(.*?)__(.*)/?', 'index.php?page_id=' . $detailsView . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
			}

			if ( $listView != false ) {
				add_rewrite_rule( $courseFolder . '/?$', 'index.php?page_id=' . $listView, 'top' );
			}
		}

		if ( get_option( 'eduadmin-options_have_changed', 'false' ) == true ) {
			flush_rewrite_rules();
			update_option( 'eduadmin-options_have_changed', false );
		}
		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
	}

	add_action( 'init', 'eduadmin_rewrite_init' );
	add_action( 'admin_init', 'eduadmin_rewrite_init' );