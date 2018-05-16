<?php
defined( 'ABSPATH' ) || die( 'This plugin must be run within the scope of WordPress.' );

function eduadmin_activate_rewrite() {
	$t = EDU()->start_timer( __METHOD__ );
	eduadmin_rewrite_init();
	flush_rewrite_rules();
	EDU()->stop_timer( $t );
}

function eduadmin_deactivate_rewrite() {
	$t = EDU()->start_timer( __METHOD__ );
	flush_rewrite_rules();
	EDU()->stop_timer( $t );
}

function eduadmin_rewrite_init() {
	$t = EDU()->start_timer( __METHOD__ );
	add_rewrite_tag( '%courseSlug%', '([^&]+)' );
	add_rewrite_tag( '%courseId%', '([^&]+)' );
	add_rewrite_tag( '%edu-login%', '([^&]+)' );
	add_rewrite_tag( '%edu-profile%', '([^&]+)' );
	add_rewrite_tag( '%edu-bookings%', '([^&]+)' );
	add_rewrite_tag( '%edu-certificates%', '([^&]+)' );
	add_rewrite_tag( '%edu-limiteddiscount%', '([^&]+)' );
	add_rewrite_tag( '%edu-logout%', '([^&]+)' );
	add_rewrite_tag( '%edu-password%', '([^&]+)' );

	$list_view    = get_option( 'eduadmin-listViewPage' );
	$login_view   = get_option( 'eduadmin-loginViewPage' );
	$details_view = get_option( 'eduadmin-detailViewPage' );
	$booking_view = get_option( 'eduadmin-bookingViewPage' );

	$object_interest_page = get_option( 'eduadmin-interestObjectPage' );
	$event_interest_page  = get_option( 'eduadmin-interestEventPage' );

	$course_folder = get_option( 'eduadmin-rewriteBaseUrl' );
	$course_folder = trim( $course_folder );
	if ( false !== $course_folder && ! empty( $course_folder ) ) {
		//if($loginView != false)
		{
			add_rewrite_rule( $course_folder . '/profile/login/?', 'index.php?page_id=' . $login_view . '&edu-login=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/myprofile/?', 'index.php?page_id=' . $login_view . '&edu-profile=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/bookings/?', 'index.php?page_id=' . $login_view . '&edu-bookings=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/card/?', 'index.php?page_id=' . $login_view . '&edu-limiteddiscount=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/certificates/?', 'index.php?page_id=' . $login_view . '&edu-certificates=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/changepassword/?', 'index.php?page_id=' . $login_view . '&edu-password=1', 'top' );
			add_rewrite_rule( $course_folder . '/profile/logout/?', 'index.php?page_id=' . $login_view . '&edu-logout=1', 'top' );
		}

		if ( false !== $booking_view ) {
			if ( false !== $event_interest_page ) {
				add_rewrite_rule( $course_folder . '/(.*?)__(.*)/book/interest/?', 'index.php?page_id=' . $event_interest_page . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
			}
			add_rewrite_rule( $course_folder . '/(.*?)__(.*)/book/?', 'index.php?page_id=' . $booking_view . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
		}

		if ( false !== $details_view ) {
			if ( $object_interest_page ) {
				add_rewrite_rule( $course_folder . '/(.*?)__(.*)/interest/?', 'index.php?page_id=' . $object_interest_page . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
			}
			add_rewrite_rule( $course_folder . '/(.*?)__(.*)/?', 'index.php?page_id=' . $details_view . '&courseSlug=$matches[1]&courseId=$matches[2]', 'top' );
		}

		if ( false !== $list_view ) {
			add_rewrite_rule( $course_folder . '/?$', 'index.php?page_id=' . $list_view, 'top' );
		}
	}

	if ( true == get_option( 'eduadmin-options_have_changed', 0 ) ) {
		flush_rewrite_rules();
		update_option( 'eduadmin-options_have_changed', 0 );
	}
	EDU()->stop_timer( $t );
}

add_action( 'init', 'eduadmin_rewrite_init' );
add_action( 'admin_init', 'eduadmin_rewrite_init' );
