<?php

function edu_decrypt_api_key( $key ) {
	$decrypted = explode( '|', base64_decode( $key ) );
	if ( 2 === count( $decrypted ) ) {
		$api_key         = new stdClass();
		$api_key->UserId = $decrypted[0];
		$api_key->Hash   = $decrypted[1];

		return $api_key;
	}

	return false;
}

function edu_get_timers() {
	if ( ! empty( $_GET['edu-showtimers'] ) && '1' === $_GET['edu-showtimers'] ) { // Input var okay.
		if ( EDU()->timers ) {
			echo '<!-- EduAdmin Booking (' . esc_html( EDU()->version ) . ") API - Timers -->\n";
			$total_value = 0;
			foreach ( EDU()->timers as $timer => $value ) {
				echo '<!-- ' . esc_html( $timer ) . ': ' . esc_html( round( $value * 1000, 2 ) ) . "ms -->\n";
				$total_value += $value;
			}
			echo '<!-- EduAdmin Total: ' . esc_html( round( $total_value * 1000, 2 ) ) . "ms -->\n";
			echo "<!-- /EduAdmin Booking API - Timers -->\n";
		}
		if ( EDU()->api->timers ) {
			echo "<!-- EduAdmin Booking Class - Timers -->\n";
			$total_value = 0;
			foreach ( EDU()->api->timers as $timer => $value ) {
				echo '<!-- ' . esc_html( $timer ) . ': ' . esc_html( round( $value * 1000, 2 ) ) . "ms -->\n";
				$total_value += $value;
			}
			echo '<!-- EduAdmin Total: ' . esc_html( round( $total_value * 1000, 2 ) ) . "ms -->\n";
			echo "<!-- /EduAdmin Booking Class - Timers -->\n";
		}

		do_action( 'eduadmin_showtimers' );
	}
}